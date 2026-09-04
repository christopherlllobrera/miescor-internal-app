<section class="bg-gray-50">
    <div id="blog-feature" class="py-8 px-4 mx-auto max-w-7xl lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm">
            <x-portal.section-heading
                title="Feature News"
                subtitle="Stay informed with the latest stories, announcements, and developments from MIESCOR. Your source for company news and industry insights."
            />
        </div>
        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

                @include('employee-portal.homepage.newspage.blog_feature', ['featuredPosts' => $posts])

        </div>
    </div>
</section>
