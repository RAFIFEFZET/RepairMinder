@extends('user_view.resources.layouts.app')
@section('title', 'Scanner')

@push('custom-css')
    

    {{-- <style>
        #reader__dashboard_section_csr{
            display: none !important;
        }
    </style> --}}
    <style>
        #reader {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin: auto;
            position: relative;
            overflow: hidden;
        }
    
        #reader video {
            /* transform: scaleX(-1); */
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    
        @media (max-width: 768px) {
            #reader {
                max-width: 300px;
            }
        }
    
        @media (max-width: 480px) {
            #reader {
                max-width: 250px;
            }
        }

        .hidden {
            display: none;
        }

        /* Tambahkan animasi dan border pada modal */
        #result .bg-white.rounded-lg {
            border: 2px solid #ddd;
            animation: fadeIn 0.3s ease-in-out forwards;
            transform: translateY(-10px);
            opacity: 0;
        }

        @keyframes fadeIn {
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header-scan {
            background-image: url("../images/templatemo-wave-header.jpg"),
                linear-gradient(#348cd2, #ffffff);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
    </style>
@endpush

@section('content')
    <div class="container flex justify-center px-4 py-8">
        @auth
        <div class="w-full max-w-lg mx-auto p-4 bg-white rounded-lg shadow-lg">
            <div class="alert alert-danger hidden" id="error" role="alert"></div>
            <div class="flex justify-center items-center col-12 md:col-6 lg:col-4 m-auto mb-4">
                <div id="reader"></div>
                <!-- Start/Stop Button -->
                <div class="mt-3 text-center">
                    <button id="toggle-scan-btn" class="btn custom-btn smoothscroll mt-3">Mulai Scan</button>
                </div>
            </div>

            <!-- Modal untuk hasil scan -->
            <div id="result" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
                    <div class="p-4 border-b header-scan">
                        <h3 class="text-xl font-bold">Hasil Scan</h3>
                    </div>
                    <div class="p-4">
                        <div id="result-content"> 
                            <div class="grid grid-cols-2 gap-4" id="detail-barang">
                                <div>
                                    <p><strong>Nama Barang:</strong> <span id="nama-barang"></span></p>
                                    <p><strong>Merk:</strong> <span id="merk"></span></p>
                                    <p><strong>Stok:</strong> <span id="stok-barang"></span></p>
                                    <p><strong>Kode Barang:</strong> <span id="kode-barang"></span></p>
                                    <p><strong>Jenis Barang:</strong> <span id="jenis-barang"></span></p>
                                    <p><strong>Nama Ruangan:</strong> <span id="lokasi-barang"></span></p>
                                    <p><strong>Kondisi:</strong> <span id="status"></span></p>
                                </div>
                                <div>
                                    <p><strong>Terakhir Diperbarui:</strong> <span id="updated-at"></span></p>
                                </div>
                            </div>
            
                            <!-- Tampilan khusus jika barang rusak -->
                            <div id="barang-rusak" class="hidden text-center text-red-600 font-bold text-xl p-4">
                                <h1><span id="nama-barang-rusak"></span> ini sedang rusak</h1>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end p-4 border-t">
                        <button id="close-btn" class="btn custom-btn smoothscroll mt-3">Tutup</button>
                        <button id="close-btn" class="btn danger-btn smoothscroll mt-3 laporpak">Lapor</button>
                    </div>
                </div>
            </div>
            

            <p id="error" class="text-red-600 mt-2 hidden"></p>
            
        </div>
        @else
        <div class="w-full max-w-lg mx-auto p-4 bg-white rounded-lg shadow-lg">
            <div class="flex justify-center items-center col-12 md:col-6 lg:col-4 m-auto mb-4">
                <h5 class="text-center">Login Dulu Gak sih 👉 <a href="{{route('login')}}">Login</a></h5>
            </div>
        </div>
        @endauth

    </div>
@endsection
@push('custom-js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" integrity="sha512-r6rDA7W6ZeQhvl8S7yRVQUKVHdexq+GAlNkNNqVC7YyIV+NwqCTJe2hDWCiffTyRNOeGEzRRJ9ifvRm/HCzGYg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        let html5QrcodeScanner = new Html5Qrcode("reader");
        let isScanning = false;

        function calculateQrboxSize() {
            const readerWidth = document.getElementById('reader').offsetWidth;
            return Math.min(350, readerWidth * 0.8);
        }

        function onScanSuccess(decodedText) {
            html5QrcodeScanner.stop().then(() => {
                isScanning = false;
                document.getElementById('toggle-scan-btn').textContent = 'Start Scan';
            }).catch(err => {
                console.error("Error stopping QR code scanner: ", err);
            });

            fetch('/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ kode_barang: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const barang = data.data;
                    const resultModal = document.getElementById('result');
                    const detailBarang = document.getElementById('detail-barang');
                    const barangRusak = document.getElementById('barang-rusak');

                    if (barang.kondisi_barang.toLowerCase() === 'rusak') {
                        document.getElementById('nama-barang-rusak').textContent = barang.nama_barang;
                        // Jika barang rusak, tampilkan pesan khusus
                        detailBarang.classList.add('hidden');
                        barangRusak.classList.remove('hidden');
                        const laporButton = document.querySelector('.laporpak');
                            if (barang.kondisi_barang.toLowerCase() === 'rusak') {
                                laporButton.classList.add('hidden');
                            } else {
                                laporButton.classList.remove('hidden');
                            }
                    } else {
                        // Jika barang tidak rusak, tampilkan detailnya
                        document.getElementById('nama-barang').textContent = barang.nama_barang;
                        document.getElementById('merk').textContent = barang.merek;
                        document.getElementById('stok-barang').textContent = barang.stok_barang;
                        document.getElementById('kode-barang').textContent = barang.kode_barang;
                        document.getElementById('jenis-barang').textContent = barang.nama_jenis_barang;
                        document.getElementById('updated-at').textContent = barang.updated_at;
                        document.getElementById('lokasi-barang').textContent = barang.nama_ruangan;
                        document.getElementById('status').textContent = barang.kondisi_barang;

                        detailBarang.classList.remove('hidden');
                        barangRusak.classList.add('hidden');
                    }

                    resultModal.classList.remove('hidden');
                    document.getElementById('error').classList.add('hidden'); // Sembunyikan pesan error
                } else {
                    document.getElementById('error').textContent = data.message;
                    document.getElementById('error').classList.remove('hidden');

                    html5QrcodeScanner.stop().then(() => {
                        isScanning = false;
                        document.getElementById('toggle-scan-btn').textContent = 'Start Scan';
                    }).catch(err => {
                        console.error("Error stopping QR code scanner: ", err);
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }


        function onScanFailure(error) {
            console.warn(`Scan error: ${error}`);
        }

        // BUAT START/STOP BUTTON
        document.getElementById('toggle-scan-btn').addEventListener('click', function() {
            document.getElementById('error').classList.add('hidden');
            document.getElementById('result').classList.add('hidden');
            if (isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('toggle-scan-btn').textContent = 'Start Scan';
                    isScanning = false;
                }).catch(err => {
                    console.error("Error stopping QR code scanner: ", err);
                });
            } else {
                html5QrcodeScanner.start(
                    { facingMode: "environment" },
                    {
                        fps: 60,
                        qrbox: calculateQrboxSize()
                    },
                    onScanSuccess,
                    onScanFailure
                ).then(() => {
                    document.getElementById('toggle-scan-btn').textContent = 'Stop Scan';
                    isScanning = true;
                }).catch(err => {
                    console.error("Error starting QR code scanner: ", err);
                });
            }
        });

        document.getElementById('close-btn').addEventListener('click', () => {
            document.getElementById('result').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');
        });


        document.querySelector('.laporpak').addEventListener('click', function() {
            const namaBarang = document.getElementById('nama-barang').textContent;
            const merkBarang = document.getElementById('merk').textContent;
            const kodeBarang = document.getElementById('kode-barang').textContent;
            const lokasiBarang = document.getElementById('lokasi-barang').textContent;

            // Redirect ke halaman laporan dengan data
            const url = `/lapor/create?nama_barang=${encodeURIComponent(namaBarang)}&merk_barang=${encodeURIComponent(merkBarang)}&kode_barang=${encodeURIComponent(kodeBarang)}&lokasi_barang=${encodeURIComponent(lokasiBarang)}`;
            window.location.href = url;
        });


        // html5QrcodeScanner.start(
        //     { facingMode: "environment" },
        //     {
        //         fps: 30,
        //         qrbox: calculateQrboxSize()
        //     },
        //     onScanSuccess,
        //     onScanFailure
        // ).catch(err => {
        //         console.error("Error starting QR code scanner: ", err);
        // });

    </script>

@endpush