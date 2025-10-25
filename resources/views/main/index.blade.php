@extends('main.layouts.app')

@section('content')
    @include('main.home.jumbotron')
    @include('main.home.billing')
    @include('main.home.logo')
    @include('main.home.news')
    @include('main.home.testimoni')



    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Pemasukan',
                    data: {!! json_encode($chartIncome ?? []) !!},
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: {!! json_encode($chartExpense ?? []) !!},
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.mySummarySwiper', {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    autoplay: true,
                    loop: true,
                    // pagination: {
                    //     el: '.swiper-pagination',
                    //     clickable: true,
                    // },
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new Swiper(".paket-swiper", {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    centeredSlides: true,
                    autoplay: true,
                    loop: true,
                });

                new Swiper(".news-swiper", {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    centeredSlides: true,
                    autoplay: true,
                    loop: true,
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new Swiper(".testimonial-swiper", {
                    slidesPerView: 1.1,
                    spaceBetween: 20,
                    centeredSlides: true,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2.2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    },
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Swiper untuk carousel logo
                new Swiper(".logo-swiper", {
                    slidesPerView: 2.5,
                    spaceBetween: 40,
                    loop: true,
                    speed: 3000, // semakin kecil semakin cepat
                    freeMode: true,
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                    },
                    allowTouchMove: false, // biar nggak bisa di-drag, jadi benar-benar auto jalan
                    breakpoints: {
                        640: {
                            slidesPerView: 3.5,
                        },
                        768: {
                            slidesPerView: 5,
                        },
                        1024: {
                            slidesPerView: 6,
                        },
                    },
                });
            });
        </script>
    @endpush
@endsection
