<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <title>Our Events - IT Club</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="common.css">
  <style>
    .event-label {
      text-align: center;
      color: var(--six-color);
      margin-bottom: 10px;
    }

    .event-container {
      display: flex;
flex-wrap:wrap;
      justify-content: center;
      align-items: center;
      gap: 20px;
      padding: 10px;
    }

    .event-card {
      padding-top: 20px;
padding-bottom: 15px;
margin:10px auto;
      width: 340px;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      border-radius: 8px;
      background: #262626;
      box-shadow: -5px 5px 10px rgba(0, 0, 0, 1),
        5px 5px 10px rgba(0, 0, 0, 1);
      transition: all 0.5s;
    }

    @media screen and (min-width:767px) {
      .event-card {
        width: 360px;
      }
    }

    .event-card-thumbnail {
      position: relative;
      display: flex;
      justify-content: center;
      width: 100%;
      height: 200px;
      overflow: hidden;
    }

    .event-card-thumbnail img {
      height: 100%;
      object-fit: center/cover;
border-radius:5px;
    }

    .event-card-body {
      width: 100%;
      padding: 1rem;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      font-size: 12px;
      color: #bdb8b8;
    }

    .event-card-title {
      color: #e8e8e8;
      font-weight: 600;
      font-size: 20px;
      letter-spacing: 1px;
    }

    .event-card-date {
width:100%;
      color: #eeeeee;
      font-size: 14px;
text-align:center;
    }

    .event-card-description {
      text-align: justify;
      font-size: 13px;
    }

    .event-card-url {
width:100px;
      display: block;
      text-decoration: none;
      font-size: 14px;
      color: #f1c40f;
      background: transparent;
      border: 2px solid #f1c40f;
      padding: 10px 10px;
      border-radius: 5px;
      transition: 0.2s ease;
      margin: auto;
      margin-top: 20px;
text-align:center;
    }

    .event-card-url:hover {
      background: #f1c40f;
      color: #000;
    }
  </style>

</head>

<body>

<?php include 'header.html'; ?>

  <h1 class="event-label">Our Events</h1>
  <div class="event-container">
  </div>

  <?php include 'footer.html'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    function decodeHTMLEntities(text) {
        const textArea = document.createElement('textarea');
        textArea.innerHTML = text;
        return textArea.value;
}
    // Use jQuery to make an AJAX request to fetch_events.php
    $.ajax({
      url: 'action/fetch_events.php',
      type: 'GET',
      dataType: 'json', // Assuming your PHP script returns JSON
      success: function(data) {
console.log(data)
        // Check if data is not empty
        if (data.response.length > 0) {
          // Clear the event container
          $('.event-container').html('');
          // Loop through the data and append events to the container
          $.each(data.response, function(index, event) {
            var description = event.event_description;
            if (description.length > 200) {
              description = description.substring(0, 200) + '...';
            }
            var formattedDate = event.event_date ? new Date(event.event_date).toLocaleDateString() : '';

            // Create an event card and append it to the container
            var eventCard = '<div class="event-card">';
            eventCard += '<div class="event-card-thumbnail">';
            eventCard += '<img src="img/events/' + event.event_imgurl + '" alt="' + event.event_title + '">';
            eventCard += '</div>';
            eventCard += '<div class="event-card-body">';
            eventCard += '<span class="event-card-title">' + event.event_title + '</span>';
            if (event.event_date) {
              eventCard += '<p class="event-card-date">Event Date: ' + formattedDate + '</p>';
            }
            eventCard += '<div class="event-card-description">' + decodeHTMLEntities(description) + '</div>';
            eventCard += '<a class="event-card-url" href="event.php?event_id=' + event.event_id + '"><i class="fas fa-calendar"></i> &nbsp;Event Info</a>';
            eventCard += '</div></div>';

            // Append the event card to the container
            $('.event-container').append(eventCard);
          });
        } else {
          // No events found
          $('.event-container').html("<p style='color:white;'>No events found.</p>");
        }
      },
      error: function() {
        // Handle any errors if the AJAX request fails
        $('.event-container').html("<p style='color:white;'>Failed to fetch events.</p>");
      }
    });
  });
</script>


</body>

</html>