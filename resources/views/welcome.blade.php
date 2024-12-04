
<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>Booking Room</title>
    
        
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
        
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
        
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
            
        
            <style>
            
            body {
            font-family: 'Roboto', sans-serif;
            background-color: #000000;  
            color: #333;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }
        h1 {
            color: #f39c12; 
            font-size: 36px;
            font-weight: 600;
            margin-top: 30px;
            text-align: center;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }
        .fc-scroller {
            overflow-y: auto !important; 
            max-height: 70vh; 
        }
        #calendar {
            max-width: 900px;
            /* max-height: 80vh; */
            margin: 50px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.5);
            padding: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .fc-toolbar-title {
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
        }
        .fc-button {
            background-color: #f39c12;
            border-color: #f39c12;
            color: black;
            border-radius: 8px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
        }
        .fc-button:hover {
            background-color: #f1c40f;
            color: black;
            transform: scale(1.05);
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            color: #333;
        }
        .modal-header {
            background-color: #f39c12;
            color: #212121;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .modal-body {
            background-color: #f9f9f9;
        }
        .modal-footer button {
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .form-control, .btn {
            border-radius: 8px;
            font-size: 14px;
            background-color: #f4f4f9;
            color: #333;
        }
        .form-group label {
            font-weight: 500;
            color: #f39c12;
        }
        .form-row .col-4 {
            padding-right: 15px;
            padding-left: 15px;
        }
        .btn-primary {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        .btn-primary:hover {
            background-color: #f1c40f;
            border-color: #f1c40f;
            transform: scale(1.05);
        }
        .fc-event {
            border-radius: 8px;
            background-color: #f39c12;
            color: black;
        
        }
        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
            }
            100% {
                transform: scale(1);
            }
        }
        .fc-event:hover {
            background-color: #f1c40f;
            color: #333;
        }
        .header_side{
            /* background-color: rgb(230, 230, 230) */
        }
            </style>
            <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
            @vite('resources/css/app.css')
        </head>
        <body>
    <nav class="bg-gray-800 p-1">
        <div class="mx-auto flex justify-between items-center">
            <!-- Brand Name positioned at the left -->
            <div class="text-white text-2xl font-bold ml-5">
                <a href="/"><img src="{{ asset('img/logo.png') }}" alt="logo" style="height: 70px"></a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-4 mr-5">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                            class="text-white font-semibold hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                            class="text-white font-semibold hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Log in
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>


       
            <div class="kontener row">
                <div class="col-md-2 mt-5  m-5 side_information hidden-scrollbar">
                    <div class="header_side p-2">
                        <p class="title_booking">Booking This Week</p>
                    </div>
                    <div id="card_kontener">

                    </div>
                </div>

                <div class="col-md-6" id="calendar"></div>

                <div class="col-md-3 mt-5 m-5">
                    <div class="card" style="border-radius: 15px">
                        <div class="card-header">
                          Information
                        </div>
                        <div class="card-body">
                         <div class="information_row d-flex align-items-center mb-3">
                            <div id="box_tradis"></div>
                            <h5  class="ms-3">Tradis Room</h5>
                            {{-- <h5>Tradis</h5> --}}
                         </div>
                         <div class="information_row d-flex align-items-center">
                            <div id="box_komodo"></div>
                            <h5  class="ms-3">Komodo Room</h5>
                         </div>
                        
                        </div>
                      </div>
                </div>
            </div>
    
            </div>
            @vite('resources/js/app.js')
            <!-- jQuery, Moment.js, FullCalendar JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script>
    $(document).ready(function () {
        var SITEURL = "{{ url('/') }}";

        function side_menu(){
                    $('#card_kontener').empty();
                    $.ajax({
                        url:  SITEURL + "/dashboard_api",
                        method: "GET",
                        success: function(datas){
                            console.log(datas)
                            datas.forEach(function(data){
                                var room = data.title ==  'Komodo Room' ? 'Komodo' : 'Tradis'
                                console.log(room)
                                $('#card_kontener').append(
                                    `
                                    <div class="card m-3">
                                        <div class="card-header ${room}">
                                        ${data.title}
                                        </div>
                                        <div class="card-body">
                                        <h5 class="card-title">${data.date_start} - ${data.day}</h5>
                                        <p>${data.time_start} - ${data.time_end}</p>
                                        <p>Booked by : ${data.name}</p>
                                        </div>
                                    </div>
                    `
                                )
                            })
                        }
                    })
                }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: false, // Nonaktifkan fitur mengedit event
            droppable: false, // Nonaktifkan fitur drag and drop
            selectable: false, // Nonaktifkan fitur memilih tanggal
            events: SITEURL + "/fullcalender",
            displayEventTime: true,
            eventRender: function(event, element) {
            
            element.find('.fc-title').append("<br><strong>" + event.name + "</strong>");

            if (event.title === "Tradis Room") {
            element.css("background-color", "#FEEE91"); 
        } else if (event.title === "Komodo") {
            element.css("background-color", "#EB5B00"); 
        }
        },
            eventClick: function(event) {
                // Tidak ada aksi ketika event diklik, hanya tampilkan modal jika diperlukan
                $('#eventModalLabel').text('View Booking');
                $('#eventName').val(event.name);
                $('#eventTitle').val(event.title);
                $('#eventDate').val(moment(event.start).format('YYYY-MM-DD'));
                $('#eventStartTime').val(moment(event.start).format('HH:mm'));
                $('#eventEndTime').val(moment(event.end).format('HH:mm'));
                $('#deleteEventButton').hide(); // Sembunyikan tombol hapus karena event tidak bisa dihapus

                $('#eventModal').modal('show');
            }
        });
        side_menu();
    });
   
</script>

            <!-- <script type="module" src="{{ asset('js/calender.js') }}"></script> -->
            <!-- Bootstrap JS -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
    

    