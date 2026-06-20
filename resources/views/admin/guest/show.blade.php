<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <x-admin.customer-profile :profile="$profile" />
        </div>
    </div>
</main>

<x-admin.footer />
