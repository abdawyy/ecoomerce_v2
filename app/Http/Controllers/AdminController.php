<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Traits\Apptraits;
use App\Models\orders;
use App\Models\User;
use App\Services\AdminCustomerProfileService;
use App\Services\AnalyticsService;
use App\Services\AdminNotificationService;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show the login form for admin.
     */
    use Apptraits;

    public $model = 'App\Models\Admin';

    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);


        // Attempt to log the admin in
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            // Redirect to admin dashboard on successful login
            return redirect()->route("admin.dashboard")->with('success', 'Login successful');
        }

        // Return with error if login failed
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
    public function logout(Request $request)
    {
        // Log out the admin user
        Auth::guard('admin')->logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page with a success message
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }





    /**
     * Show the registration form for admin.
     */
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Handle the admin registration request.
     */
    public function register(Request $request)
    {
        // Validate the registration request
        $request->validate([
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create a new admin
        Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect to the admin login page after registration
        return redirect()->route('admin.login')->with('success', 'Registration successful. Please log in.');
    }

    /**
     * Show the admin dashboard (only accessible when logged in).
     */
    public function index(AnalyticsService $analytics, AdminNotificationService $notifications)
    {
        $totalUsers = User::count();
        $totalOrders = orders::count();
        $totalAmount = (float) orders::whereIn('status', ['Completed', 'completed'])->sum('total_amount');

        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        $weekStart = Carbon::today()->subDays(6)->startOfDay();

        $todayKpis = $analytics->dashboardKpis($todayStart, $todayEnd);
        $todaySales = $todayKpis['current']['sales'];
        $weekSales = $analytics->salesSummary($weekStart, $todayEnd);

        $notify = $notifications->counts();
        $pendingOrders = $notify['pending_orders'];
        $liveCount = $analytics->liveVisitorCount();
        $latestOrderId = (int) orders::max('id');
        $latestUserId = (int) User::max('id');
        $latestGuestId = (int) \App\Models\GuestUser::max('id');
        $latestMessageId = (int) \App\Models\Message::max('id');

        $activityFeed = $notifications->activityFeed(15);

        $chartRevenue = $analytics->revenueTrend($weekStart, $todayEnd);
        $chartViews = $analytics->viewsTrend($weekStart, $todayEnd);

        return view('admin.index', compact(
            'totalUsers',
            'totalOrders',
            'totalAmount',
            'todaySales',
            'weekSales',
            'todayKpis',
            'pendingOrders',
            'notify',
            'liveCount',
            'latestOrderId',
            'latestUserId',
            'latestGuestId',
            'latestMessageId',
            'activityFeed',
            'chartRevenue',
            'chartViews',
        ));
    }

    public function dashboardStats(Request $request, AnalyticsService $analytics, AdminNotificationService $notifications)
    {
        $sinceOrderId = (int) $request->get('since_order_id', 0);
        $sinceUserId = (int) $request->get('since_user_id', 0);
        $sinceGuestId = (int) $request->get('since_guest_id', 0);
        $sinceMessageId = (int) $request->get('since_message_id', 0);

        $notify = $notifications->counts();

        $newOrdersQuery = orders::with(['user', 'guestUser'])
            ->when($sinceOrderId > 0, fn ($q) => $q->where('id', '>', $sinceOrderId))
            ->orderByDesc('id')
            ->limit(5);

        $newUsersQuery = User::query()
            ->when($sinceUserId > 0, fn ($q) => $q->where('id', '>', $sinceUserId))
            ->orderByDesc('id')
            ->limit(5);

        $newGuestsQuery = \App\Models\GuestUser::query()
            ->when($sinceGuestId > 0, fn ($q) => $q->where('id', '>', $sinceGuestId))
            ->orderByDesc('id')
            ->limit(5);

        $newMessagesQuery = \App\Models\Message::query()
            ->when($sinceMessageId > 0, fn ($q) => $q->where('id', '>', $sinceMessageId))
            ->orderByDesc('id')
            ->limit(5);

        return response()->json([
            'live_count' => $analytics->liveVisitorCount(),
            'pending_orders' => $notify['pending_orders'],
            'unread_messages' => $notify['unread_messages'],
            'new_users' => $notify['new_users'],
            'new_guests' => $notify['new_guests'],
            'today_revenue' => (float) $analytics->salesSummary(Carbon::today()->startOfDay(), Carbon::today()->endOfDay())['revenue'],
            'today_orders' => $analytics->salesSummary(Carbon::today()->startOfDay(), Carbon::today()->endOfDay())['orders'],
            'latest_order_id' => (int) orders::max('id'),
            'latest_user_id' => (int) User::max('id'),
            'latest_guest_id' => (int) \App\Models\GuestUser::max('id'),
            'latest_message_id' => (int) \App\Models\Message::max('id'),
            'new_orders' => $newOrdersQuery->get()->map(fn ($order) => [
                'id' => $order->id,
                'total' => (float) $order->total_amount,
                'status' => $order->status,
                'customer' => $order->user?->name ?? $order->guestUser?->name ?? __('dashboard.guest'),
                'created_at' => $order->created_at?->diffForHumans(),
                'url' => route('order.show', $order->id),
            ]),
            'new_users' => $newUsersQuery->get()->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->diffForHumans(),
                'url' => route('user.show', $user->id),
            ]),
            'new_guests' => $newGuestsQuery->get()->map(fn ($guest) => [
                'id' => $guest->id,
                'name' => $guest->name,
                'email' => $guest->email,
                'created_at' => $guest->created_at?->diffForHumans(),
                'url' => route('admin.guest.show', $guest->id),
            ]),
            'new_messages' => $newMessagesQuery->get()->map(fn ($message) => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'preview' => \Illuminate\Support\Str::limit($message->message, 60),
                'created_at' => $message->created_at?->diffForHumans(),
                'url' => route('admin.contact.list'),
            ]),
        ]);
    }
    public function list(Request $request)
    {
        // Get the search parameter from the request
        $search = $request->input('search');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'Name' => 'username',
            'Email' => 'email',
            'Created At' => 'created_at',

        ];

        // Use the search scope defined in the AppTrait
        $admins = Admin::search($search, $headerMap)->paginate(10)->appends(['search' => $search]); // 👈 This preserves the search query;

        // Define the headers for the table
        $headers = ['ID', 'Name', 'Email', 'Created At', 'Action'];

        // Prepare the rows by mapping through the admins collection
        $rows = $admins->map(function ($admin) {
            return [
                'ID' => $admin->id,
                'Name' => $admin->username,
                'Email' => $admin->email,
                'Created At' => $admin->created_at->format('m/d/Y'),
                'is_active' => $admin->is_active
            ];
        });
        $url = '/admin';

        // Return the view with headers and rows data
        return view('admin.admin.list', compact('headers', 'rows', 'admins', 'search', 'url'));
    }

    public function delete($id)
    {
        // Call the deleteRecord function from the trait
        $isDeleted = self::deleteRecord($this->model, $id);

        // Handle the flash message based on the result
        if ($isDeleted) {
            // Success flash message
            return redirect()->back()->with('success', 'Record deleted successfully.');
        } else {
            // Failure flash message
            return redirect()->back()->with('error', 'Failed to delete the record. Record may not exist.');
        }
    }
    public function orderList(Request $request)
    {
        // Get the filter parameters from the request
        $search = $request->input('search');
        $status = $request->input('status');
        $id = $request->input('id');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'User' => 'user.name',
            'Email' => 'user.email',
            'Guest User' => 'guestUser.name',
            'Guest Email' => 'guestUser.email',
            'Total Amount' => 'total_amount',
            'Status' => 'status',
            'Code' => 'discountCodes.code',
            'City' => 'cities.name',
            'Created At' => 'created_at',
        ];

        // Build query with filters
        $query = orders::with('user', 'guestUser', 'discountCodes', 'cities');

        // Apply search filter
        if ($search) {
            $query = $query->search($search, $headerMap);
        }

        // Apply id filter (exact match)
        if ($id) {
            $query = $query->where('id', $id);
        }
        
        // Apply status filter
        if ($status) {
            $query = $query->where('status', $status);
        }

        // Paginate and preserve query parameters
        $data = $query->paginate(10)->appends(['search' => $search, 'status' => $status, 'id' => $id]); // 👈 This preserves filter parameters

        // Define the headers for the table
        $headers = ['ID', 'Name', 'Email', 'Total Amount', 'Code', 'City', 'Status', 'Created At', 'Action'];

        // Prepare the rows by mapping through the data collection
        $rows = $data->map(function ($order) {
            $name = $order->user->name ?? $order->guestUser->name ?? 'N/A';
            $email = $order->user->email ?? $order->guestUser->email ?? 'N/A';
            $code = $order->discountCodes->code ?? 'N/A';
            $city = $order->cities->name ?? 'N/A';
            $createdAt = $order->created_at ? $order->created_at->format('m/d/Y') : 'N/A';

            return [
                'ID' => $order->id,
                'Name' => $name,
                'Email' => $email,
                'Total Amount' => $order->total_amount,
                'Code' => $code,
                'City' => $city,
                'Status' => $order->status,
                'Created At' => $createdAt,
            ];
        });


        $url = '/admin/order';
        return view('admin.order.list', compact('headers', 'rows', 'data', 'search', 'url'));
    }
    public function orderShow($id)
    {
        // Retrieve the order with its related data, including product and size for orderItems
        $order = orders::with([
            'user',
            'user.address',            // Load user and their address
            'guestUser',               // In case the order belongs to a guest
            'guestUser.address',       // Guest user address
            'discountCodes',           // Discount codes
            'cities',                  // City of the order
            'orderItems.product',      // Product details
            'orderItems.productItems', // Product sizes/items
            'payments',
            'address'          // Load all payments related to the order
        ])->find($id);

        // Check if the order exists
        if (!$order) {
            // Redirect back with an error message if the order is not found
            return redirect()->back()->with('error', 'Order not found');
        }

        // Pass the order data to the view
        return view('admin.order.show', compact('order'));
    }

    public function downloadInvoice($id, \App\Services\PdfService $pdf)
    {
        $order = orders::with([
            'user', 'guestUser', 'discountCodes', 'cities', 'address',
            'orderItems.product', 'payments',
        ])->findOrFail($id);

        return $pdf->streamInvoice($order);
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = orders::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        // Validate and update the status
        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Completed,Cancelled,pending,completed',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
    public function userShow($id, AdminCustomerProfileService $profiles)
    {
        $user = User::find($id);

        if (! $user) {
            return redirect()->back()->with('error', 'user not found');
        }

        $profile = $profiles->fromUser($user);

        return view('admin.user.show', compact('profile'));
    }
    public function toggleUserStatus($id)
    {
        $admin = Admin::findOrFail($id);

        self::toggleStatus($admin); // now using self
        return back()->with('success', 'Status toggled');
    }
}
