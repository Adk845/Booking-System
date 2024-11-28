<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Room</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- jQuery, Moment.js, FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .fc-toolbar-title {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Booking Room Calendar</h1>
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

        
                <div class="form-group">
                    <label for="eventName">Your Name</label>
                    <input type="text" class="form-control" id="eventName" placeholder="Enter your name">
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
            </div>
        </div>
    </div>
</div>




   
    <script>
    $(document).ready(function () {
        var SITEURL = "{{ url('/') }}";

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
            views: {
                month: {
                    eventRender: function(event, element) {
                        if (event.start) {
                            var startTime = moment(event.start).format('HH:mm');
                            element.find('.fc-title').append('<br><span class="fc-time">' + startTime + '</span>');
                        }
                    }
                },
                agendaWeek: {
                    allDaySlot: false,
                    slotLabelFormat: 'HH:mm'
                },
                agendaDay: {
                    allDaySlot: false,
                    slotLabelFormat: 'HH:mm'
                }
            },
            editable: true,
            droppable: true,
            events: SITEURL + "/fullcalender",
            displayEventTime: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
              
                $('#eventModal').modal('show');

              
                var date = moment(start).format('YYYY-MM-DD');
                var startTime = moment(start).format('HH:mm');
                var endTime = moment(end).format('HH:mm');

              
                $('#eventDate').val(date);
                $('#eventStartTime').val(startTime); 
                $('#eventEndTime').val(endTime); 

                
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

                                // Reset input fields
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
            },
            eventClick: function(event) {
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
                        }
                    });
                }
            }
        });
    });

   
    function displayMessage(message) {
        toastr.success(message, 'Booking');
    }
</script>



    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
