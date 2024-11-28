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
        background-color: #f4f4f9;  
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
        max-width: 1000px;
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
        </style>
    </head>
    <body>
        <div class="container">
            <div id="calendar"></div>
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
                        <!-- <div class="form-group">
                            <label for="eventName">Your Name</label>
                            <input type="text" class="form-control" id="eventName" placeholder="Enter your name">
                        </div> -->

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
                        <button type="button" class="btn btn-primary" id="saveEventButton">Save</button>
                        <button type="button" class="btn btn-danger" id="deleteEventButton" style="display: none;">Delete</button>

                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery, Moment.js, FullCalendar JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
    $(document).ready(function () {
        var SITEURL = "{{ url('/') }}";
        var loggedInUserName = "{{ Auth::user()->name }}";  

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
    editable: true,
    droppable: true,
    events: SITEURL + "/fullcalender",
    displayEventTime: true,
    selectable: true,
    selectHelper: true,

    eventRender: function(event, element) {
        
        element.find('.fc-title').append("<br><strong>" + event.name + "</strong>");
    },

    select: function(start, end, allDay) {
        $('#eventModalLabel').text('Create Booking');
        $('#eventName').val(loggedInUserName);  
        $('#eventTitle').val('');
        $('#eventDate').val(moment(start).format('YYYY-MM-DD'));
        $('#eventStartTime').val(moment(start).format('HH:mm'));
        $('#eventEndTime').val(moment(end).format('HH:mm'));
        $('#deleteEventButton').hide();

        $('#saveEventButton').off('click').on('click', function() {
            var name = $('#eventName').val();  
            var title = $('#eventTitle').val();
            var date = $('#eventDate').val();
            var startTime = $('#eventStartTime').val();
            var endTime = $('#eventEndTime').val();

            if (name && title && date && startTime && endTime) {
                var startFormatted = moment(date + ' ' + startTime).format("YYYY-MM-DD HH:mm:ss");
                var endFormatted = moment(date + ' ' + endTime).format("YYYY-MM-DD HH:mm:ss");

                $.ajax({
                    url: SITEURL + "/fullCalenderAjax",
                    type: "POST",
                    data: {
                        name: name,
                        title: title,
                        start: startFormatted,
                        end: endFormatted,
                        type: 'add'
                    },
                    success: function(data) {
                        displayMessage("Booking Created Successfully");
                        calendar.fullCalendar('renderEvent', {
                            id: data.id,
                            name: name,  
                            title: title,
                            start: startFormatted,
                            end: endFormatted,
                            allDay: allDay
                        }, true);
                        calendar.fullCalendar('unselect');
                        $('#eventModal').modal('hide');
                        $('#eventName').val('');
                        $('#eventTitle').val('');
                        $('#eventDate').val('');
                        $('#eventStartTime').val('');
                        $('#eventEndTime').val('');
                    }
                });
            } else {
                alert("Please fill in all fields.");
            }
        });

        $('#eventModal').modal('show');
    },

    eventClick: function(event) {
        $('#eventModalLabel').text('Edit Booking');
        $('#eventName').val(event.name);  
        $('#eventTitle').val(event.title);
        $('#eventDate').val(moment(event.start).format('YYYY-MM-DD'));
        $('#eventStartTime').val(moment(event.start).format('HH:mm'));
        $('#eventEndTime').val(moment(event.end).format('HH:mm'));
        $('#deleteEventButton').show();

        $('#saveEventButton').off('click').on('click', function() {
            var name = $('#eventName').val();  
            var title = $('#eventTitle').val();
            var date = $('#eventDate').val();
            var startTime = $('#eventStartTime').val();
            var endTime = $('#eventEndTime').val();

            if (name && title && date && startTime && endTime) {
                var startFormatted = moment(date + ' ' + startTime).format("YYYY-MM-DD HH:mm:ss");
                var endFormatted = moment(date + ' ' + endTime).format("YYYY-MM-DD HH:mm:ss");

                $.ajax({
                    url: SITEURL + "/fullCalenderAjax",
                    type: "POST",
                    data: {
                        id: event.id,
                        name: name,
                        title: title,
                        start: startFormatted,
                        end: endFormatted,
                        type: 'update'
                    },
                    success: function(data) {
                        displayMessage("Booking Updated Successfully");

                        event.name = name;
                        event.title = title;
                        event.start = startFormatted;
                        event.end = endFormatted;

                        calendar.fullCalendar('updateEvent', event);
                        $('#eventModal').modal('hide');
                    }
                });
            } else {
                alert("Please fill in all fields.");
            }
        });

        $('#deleteEventButton').off('click').on('click', function() {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: SITEURL + '/fullCalenderAjax',
                    data: {
                        id: event.id,
                        type: 'delete'
                    },
                    success: function(response) {
                        calendar.fullCalendar('removeEvents', event.id);
                        displayMessage("Booking Deleted Successfully");
                        $('#eventModal').modal('hide');
                    }
                });
            }
        });

        $('#eventModal').modal('show');
    },

    eventDrop: function(event, delta) {
        var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD HH:mm:ss");

        $.ajax({
            url: SITEURL + '/fullCalenderAjax',
            data: {
                name: event.name,
                title: event.title,
                start: start,
                end: end,
                id: event.id,
                type: 'update'
            },
            type: "POST",
            success: function(response) {
                displayMessage("Booking Updated Successfully");
            }
        });
    }
});

function displayMessage(message) {
    toastr.success(message, 'Booking');
}


        function displayMessage(message) {
            toastr.success(message, 'Booking');
        }
    });
</script>



        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

</x-app-layout>
