<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Traits\Apptraits;

class SeasonController extends Controller
{
    use Apptraits;

    public $model = 'App\Models\Season';
    public $url = '/admin/season';

    public function edit(Request $request, $id = null)
    {
        $model = $this->model::find($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $model = self::updateOrCreate($this->model, ['id' => $id], [
                'name' => $request->name,
            ]);

            if ($model) {
                return $this->redirectToAdminList($request, 'season.list', 'seasons', $model->id, __('admin.saved'));
            }

            return $this->redirectToAdminList($request, 'season.list', 'seasons', $id, null, __('admin.save_failed'));
        }

        return view('admin.season.edit', compact('model'));
    }

    public function list(Request $request)
    {
        $this->rememberAdminListUrl('seasons');
        $search = $request->input('search');

        $headerMap = [
            'ID' => 'id',
            'Name' => 'name',
            'Created At' => 'created_at',
        ];

        $data = Season::search($search, $headerMap)->paginate(10)->appends(['search' => $search]);
        $headers = ['ID', 'Name', 'Created At', 'Action'];

        $rows = $data->map(function ($season) {
            return [
                'ID' => $season->id,
                'Name' => $season->name,
                'Created At' => $season->created_at->format('m/d/Y'),
                'href' => route('season.edit', $season->id),
                'is_active' => $season->is_active,
            ];
        });

        $url = $this->url;

        return view('admin.season.list', compact('headers', 'rows', 'data', 'search', 'url'));
    }

    public function delete($id)
    {
        $isDeleted = self::deleteRecord($this->model, $id);

        if ($isDeleted) {
            return redirect()->back()->with('success', __('admin.record_deleted'));
        }

        return redirect()->back()->with('error', __('admin.record_delete_failed'));
    }

    public function toggleUserStatus($id)
    {
        $season = Season::findOrFail($id);
        self::toggleStatus($season);

        return back()->with('success', __('admin.status_toggled'));
    }
}
