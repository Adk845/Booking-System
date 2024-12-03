<x-app-layout>
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
    
            <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eventModalLabel">Create Booking</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-row d-flex justify-content-between">
                                <div class="form-group col-4">
                                    <label for="eventDate">Date</label>
                                    <input type="date" class="form-control" id="eventDate">
                                </div>
                                <div class="form-group col-4">
                                    <label for="eventStartTime">Start Time</label>
                                    <input type="time" class="form-control" id="eventStartTime">
                                </div>
                                <div class="form-group col-4">
                                    <label for="eventEndTime">End Time</label>
                                    <input type="time" class="form-control" id="eventEndTime">
                                </div>
                            </div>
    
                            <div class="form-group">
                                <!-- <label for="eventName">Your Name</label> -->
                                <input type="text" class="form-control" id="eventName" placeholder="Enter your name" style="display: none;">
                            </div>
    
                            <div class="form-group">
                                <label for="eventTitle">Room</label>
                                <select class="form-control" id="eventTitle">
                                    <option value="Komodo Room">Komodo Room</option>
                                    <option value="Tradis Room">Tradis Room</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveEventButton">
                                <p id="tulisanSave" style="margin: 0px">
                                    Save
                                </p>
                                <div id="loading" style="display: none">
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span class="visually-hidden" role="status">Loading...</span>
                                </div>
                            </button>
                            <button type="button" class="btn btn-danger" id="deleteEventButton" style="display: none;">Delete</button>
    
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
                 var SITEURL = "{{ url('/') }}";
                 var loggedInUserName = "{{ Auth::user()->name }}";  
                 var user_name = "{{ Auth::user()->name }}";
                 var user_email = "{{ Auth::user()->email }}"
            </script>
            <script type="module" src="{{ asset('js/calender.js') }}"></script>
            <!-- Bootstrap JS -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
    
    </x-app-layout>
    