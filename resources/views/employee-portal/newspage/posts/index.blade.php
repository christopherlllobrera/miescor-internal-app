

<div class="max-w-screen-4xl mx-auto w-full flex flex-col">
    @include('employee-portal.navigation')

    <main class="grow pt-20 md:pt-24">
        <section class="bg-white">
            <div class="py-8 px-8 mx-auto max-w-screen-2xl sm:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center lg:mb-16 mb-8">
                    <h2 class="text-3xl sm:text-3xl md:text-4xl mt-8 lg:text-5xl font-bold mb-4 sm:mb-6 tracking-tight text-[#111827]">
                        Latest News
                    </h2>
                    <p class="font-normal text-[#6B7280] sm:text-xl">
                        Stay informed with the latest stories, announcements, and developments from MIESCOR. Your source for
                        company news and industry insights.
                    </p>
                </div>

                <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                    @include('employee-portal.newspage.bloglist')
                </div>
            </div>
        </section>
    </main>
    @include('employee-portal.footer')
</div>
