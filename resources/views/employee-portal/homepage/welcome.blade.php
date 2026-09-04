<x-portal.layout title="I AM MIESCOR">
    <x-portal.navigation />
    @include('employee-portal.homepage.heropage', ['carousels' => $carousels])
    @include('employee-portal.homepage.department-main-folder', ['departments' => $departments])
    @include('employee-portal.homepage.newspage', ['posts' => $posts])
    @include('employee-portal.homepage.events', ['events' => $events])
    <x-portal.footer />
</x-portal.layout>
