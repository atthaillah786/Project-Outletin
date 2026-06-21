@extends('layouts.auth')

@section('title', 'Home - Outletin')

@section('content')
<header class="mx-auto -mt-20 max-w-[1440px] px-3 pt-24 md:px-6">
    <section
        class="relative min-h-[760px] overflow-hidden rounded-[2rem] bg-cover bg-center premium-glow"
        style="background-image: url('{{ asset('images/home.jpg') }}')"
    >
        <div class="absolute inset-0 bg-gradient-to-br from-ink/78 via-oxblood/62 to-taupe/48"></div>
        <div class="absolute inset-x-0 bottom-0 h-44 bg-gradient-to-t from-ivory to-transparent"></div>

        <div class="relative z-10 flex min-h-[760px] items-end px-5 pb-10 pt-28 md:px-12 lg:px-16">
            <div class="grid w-full items-end gap-10 lg:grid-cols-[1.15fr_0.85fr]">
                <div data-reveal>
                    <span class="mb-5 inline-flex rounded-full border border-white/25 bg-white/15 px-4 py-2 text-xs font-bold uppercase tracking-normal text-ivory backdrop-blur-md">
                        Franchise command center
                    </span>

                    <h1 class="max-w-4xl text-5xl font-extrabold leading-[1.04] text-white md:text-7xl">
                        Outletin
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-ivory/88 md:text-lg">
                        Platform manajemen waralaba untuk pemilik brand dan mitra outlet yang ingin memantau operasional, peluang ekspansi, dan performa bisnis dalam satu pengalaman yang rapi.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ url('/login') }}" class="premium-button">
                            Coba Sekarang
                        </a>
                        <a href="{{ url('/outlet') }}" class="premium-button-soft bg-white/20 text-white hover:text-oxblood">
                            Telusuri Brand
                        </a>
                    </div>
                </div>

                <div class="premium-card bg-white/18 p-5 text-white backdrop-blur-xl" data-reveal>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-2xl bg-ivory p-4 text-ink">
                            <p class="text-xs font-bold text-taupe">Outlet</p>
                            <p class="mt-2 text-2xl font-extrabold">Multi</p>
                        </div>
                        <div class="rounded-2xl bg-linen p-4 text-ink">
                            <p class="text-xs font-bold text-taupe">Brand</p>
                            <p class="mt-2 text-2xl font-extrabold">Live</p>
                        </div>
                        <div class="rounded-2xl bg-oxblood p-4 text-white">
                            <p class="text-xs font-bold text-white/70">Report</p>
                            <p class="mt-2 text-2xl font-extrabold">Smart</p>
                        </div>
                    </div>
                    <img
                        src="{{ asset('images/home1.jpg') }}"
                        alt="Outletin"
                        class="mt-4 aspect-[4/3] w-full rounded-3xl object-cover shadow-2xl"
                    >
                </div>
            </div>
        </div>
    </section>
</header>

<main>
    <section class="mx-auto max-w-7xl px-4 py-20">
        <div class="mb-12 max-w-3xl" data-reveal>
            <p class="mb-3 text-sm font-extrabold uppercase tracking-normal text-oxblood">Operational clarity</p>
            <h2 class="premium-section-title">
                Semua yang Anda butuhkan untuk mengelola jaringan outlet.
            </h2>
            <p class="premium-muted mt-5">
                Fokus pada alur kerja harian: monitoring outlet, inventori, dan laporan. UI dibuat lapang agar data tetap mudah dipindai.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <article class="premium-card premium-card-hover p-8" data-reveal>
                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-oxblood text-2xl font-extrabold text-white shadow-[0_16px_36px_rgb(85,11,20,0.24)]">01</div>
                <h3 class="text-xl font-extrabold text-ink">Manajemen Outlet</h3>
                <p class="premium-muted mt-3">Kelola banyak outlet dari satu dashboard dan pantau status cabang dengan ritme operasional yang lebih jelas.</p>
                <ul class="mt-6 space-y-3 text-sm font-semibold text-taupe">
                    <li>Multi-outlet support</li>
                    <li>Real-time monitoring</li>
                    <li>Performance analytics</li>
                </ul>
            </article>

            <article class="premium-card premium-card-hover p-8" data-reveal>
                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-linen text-2xl font-extrabold text-oxblood shadow-[0_16px_36px_rgb(126,105,97,0.20)]">02</div>
                <h3 class="text-xl font-extrabold text-ink">Manajemen Bahan Baku</h3>
                <p class="premium-muted mt-3">Kontrol stok bahan baku agar suplai, pemborosan, dan kebutuhan outlet bisa dikelola lebih presisi.</p>
                <ul class="mt-6 space-y-3 text-sm font-semibold text-taupe">
                    <li>Auto stock alerts</li>
                    <li>Supplier management</li>
                    <li>Waste tracking</li>
                </ul>
            </article>

            <article class="premium-card premium-card-hover p-8" data-reveal>
                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-taupe text-2xl font-extrabold text-white shadow-[0_16px_36px_rgb(126,105,97,0.24)]">03</div>
                <h3 class="text-xl font-extrabold text-ink">Laporan Keuangan</h3>
                <p class="premium-muted mt-3">Lihat income, expense, dan profit dengan tampilan yang bersih untuk keputusan bisnis lebih cepat.</p>
                <ul class="mt-6 space-y-3 text-sm font-semibold text-taupe">
                    <li>Automated reports</li>
                    <li>Profit & loss tracking</li>
                    <li>Export-ready insight</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-20">
        <div class="relative overflow-hidden rounded-[2rem] bg-oxblood px-6 py-12 shadow-[0_24px_80px_rgb(85,11,20,0.20)] md:px-12 lg:px-16" data-reveal>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_20%,rgb(203,192,178,0.32),transparent_32rem)]"></div>
            <div class="relative grid items-center gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                <div>
                    <h2 class="text-4xl font-extrabold leading-tight text-white md:text-5xl">
                        Bangun ekspansi brand dengan pengalaman yang lebih rapi.
                    </h2>
                    <p class="mt-5 max-w-xl leading-8 text-ivory/82">
                        Pemilik brand dapat mengelola data franchise, sementara mitra bisa menemukan peluang dan mengajukan outlet tanpa proses yang terasa berat.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 text-white backdrop-blur-md">
                        <h4 class="text-lg font-extrabold">Untuk Pemilik Brand</h4>
                        <p class="mt-3 text-sm leading-7 text-ivory/78">Digitalisasi model waralaba dan kelola pengajuan mitra dengan alur yang lebih elegan.</p>
                        <a href="{{ url('/login') }}" class="premium-button mt-5 w-full bg-gradient-to-r from-linen to-ivory text-oxblood">
                            Mulai Sekarang
                        </a>
                    </div>

                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 text-white backdrop-blur-md">
                        <h4 class="text-lg font-extrabold">Untuk Mitra</h4>
                        <p class="mt-3 text-sm leading-7 text-ivory/78">Telusuri brand yang tersedia dan kelola portofolio outlet Anda dari satu tempat.</p>
                        <a href="{{ url('/outlet') }}" class="premium-button-soft mt-5 w-full bg-white/10 text-white hover:text-oxblood">
                            Telusuri Brand
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
