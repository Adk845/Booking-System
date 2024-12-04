$(document).ready(function () {

    function side_menu() {
        $('#card_kontener').empty();
        $.ajax({
            url: "http://127.0.0.1:8000/dashboard_api",
            method: "GET",
            success: function (datas) {
                console.log(datas);
                datas.forEach(function (data) {
                    var room = data.title == 'Komodo Room' ? 'Komodo' : 'Tradis';
                    console.log(room);
                    $('#card_kontener').append(
                        `
                        <div class="card m-3">
                            <div class="card-header ${room}">
                                ${data.title}
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${data.date_start} - ${data.day}</h5>
                                <p>${data.time_start} - ${data.time_end}</p>
                                <p>Booked by: ${data.name}</p>
                            </div>
                        </div>
                    `
                    );
                });
            }
        });
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
        editable: true,
        droppable: true,
        events: SITEURL + "/fullcalender",
        displayEventTime: true,
        selectable: true,
        selectHelper: true,

        eventRender: function (event, element) {
            element.find('.fc-title').append("<br><strong>" + event.name + "</strong>");

            if (event.title === "Tradis Room") {
                element.css("background-color", "#FEEE91");
            } else if (event.title === "Komodo") {
                element.css("background-color", "#EB5B00");
            }
        },

        select: function (start, end, allDay) {
            $('#eventModalLabel').text('Create Booking');
            $('#eventName').val(loggedInUserName);
            $('#eventTitle').val('');
            $('#eventDate').val(moment(start).format('YYYY-MM-DD'));
            $('#eventStartTime').val(moment(start).format('HH:mm'));
            $('#eventEndTime').val(moment(end).format('HH:mm'));
            $('#eventDescription').val('');  // Clear the description field
            $('#deleteEventButton').hide();

            $('#saveEventButton').off('click').on('click', function () {
                $('#tulisanSave').hide();
                $('#loading').show();
                $('#saveEventButton').prop('disabled', true);
                var name = $('#eventName').val();
                var title = $('#eventTitle').val();
                var date = $('#eventDate').val();
                var startTime = $('#eventStartTime').val();
                var endTime = $('#eventEndTime').val();
               var desc = $('#eventDescription').val();  // Get the description

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
                            desc: desc,  // Send description data
                            user_email: user_email,
                            user_name: user_name,
                            type: 'add'
                        },
                        success: function (data) {
                            displayMessage("Booking Created Successfully");
                            calendar.fullCalendar('renderEvent', {
                                id: data.id,
                                name: name,
                                title: title,
                                start: startFormatted,
                                end: endFormatted,
                                desc: desc,  // Include description
                                allDay: allDay
                            }, true);
                            calendar.fullCalendar('unselect');
                            $('#eventModal').modal('hide');
                            $('#eventName').val('');
                            $('#eventTitle').val('');
                            $('#eventDate').val('');
                            $('#eventStartTime').val('');
                            $('#eventEndTime').val('');
                            $('#eventDescription').val('');  // Clear the description field
                            side_menu();
                        }
                    });
                } else {
                    alert("Please fill in all fields.");
                }
            });

            $('#eventModal').modal('show');
        },

        eventClick: function (event) {
            $('#eventModalLabel').text('Edit Booking');
            $('#eventName').val(event.name);
            $('#eventTitle').val(event.title);
            $('#eventDate').val(moment(event.start).format('YYYY-MM-DD'));
            $('#eventStartTime').val(moment(event.start).format('HH:mm'));
            $('#eventEndTime').val(moment(event.end).format('HH:mm'));
            $('#eventDescription').val(event.desc);  // Fill the description field
            $('#deleteEventButton').show();

            $('#saveEventButton').off('click').on('click', function () {
                var name = $('#eventName').val();
                var title = $('#eventTitle').val();
                var date = $('#eventDate').val();
                var startTime = $('#eventStartTime').val();
                var endTime = $('#eventEndTime').val();
               var desc = $('#eventDescription').val();  // Get the description

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
                            desc: desc,  // Send updated description
                            type: 'update'
                        },
                        success: function (data) {
                            displayMessage("Booking Updated Successfully");

                            event.name = name;
                            event.title = title;
                            event.start = startFormatted;
                            event.end = endFormatted;
                            event.desc = desc;  // Update desc in the event object

                            calendar.fullCalendar('updateEvent', event);
                            $('#eventModal').modal('hide');
                            side_menu();
                        }
                    });

                } else {
                    alert("Please fill in all fields.");
                }
            });

            $('#deleteEventButton').off('click').on('click', function () {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/fullCalenderAjax',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function (response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            displayMessage("Booking Deleted Successfully");
                            $('#eventModal').modal('hide');
                        }
                    });
                }
                side_menu();
            });

            $('#eventModal').modal('show');
        },

        eventDrop: function (event, delta) {
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
                success: function (response) {
                    displayMessage("Booking Updated Successfully");
                }
            });
            side_menu();
        }
    });

    function displayMessage(message) {
        toastr.success(message, 'Booking');
    }

    side_menu();
});
