$(document).ready(function() {
  $.ajax({
    url: '../../action/get_searches.php',
    type: 'get',
    data: {
      query: query, type: type
    },
    dataType: 'json',
    beforeSend: function() {
      $('.searched-cards').html('<p style="color:#B9E0F2;font-size:16px;">Searching...</p>')
    },
    success: function(data) {
      var lastIndex;
      if (data['status'] == 'empty') {
        $('.searched-cards').html('<p style="color:#B9E0F2;font-size:17px;">No results found.</p>')
      } else if (data['status'] == 'success') {
         var numToShow = Math.min(data.results.length, 7);
      
        $('.searched-cards').html('');
      $.each(data.results.slice(0, numToShow), function(index, result) {
        var html = '<div class="searched-card card">';
        
        if (result.thumbnail != "") {
          html += '<div class="searched-img-div blog-img-div">';
          html += '<img src="' + result.thumbnail + '" alt="' + result.type + '" class="searched-img blog-img">';
          html += '</div>';
        }
        
        html += '<div class="searched-details-div details-div">';
        html += '<div class="searched-extrainfo extrainfo">';
        html += '<div class="searched-type type">' + result.type + '</div>';
        html += '<div class="searched-meta-date meta-date">' + result.datetime + '</div>';
        html += '</div>';
        
        html += '</div>';
        
        html += '<div class="searched-title title">';
        html += '<a href="' + result.url + '">' + result.title + '</a>';
        html += '</div>';
        
        html += '<div class="searched-link link">';
        html += '<a href="' + result.url + '">Read More »</a>';
        html += '</div>';
        
        html += '</div>';
        
        $('.searched-cards').append(html);
        lastIndex = index + 1;
      });

      if (data.results.length > lastIndex) {
        $('.searched-more-btn').show().click(function() {
          var end = lastIndex + 5;
          var remainingResults = data.results.slice(lastIndex, end);

          $.each(remainingResults, function(index, result) {
            var html = '<div class="searched-card card">';
            
            if (result.thumbnail != "") {
              html += '<div class="searched-img-div blog-img-div">';
              html += '<img src="' + result.thumbnail + '" alt="' + result.type + '" class="searched-img blog-img">';
              html += '</div>';
            }
            
            html += '<div class="searched-details-div details-div">';
            html += '<div class="searched-extrainfo extrainfo">';
            html += '<div class="searched-type type">' + result.type + '</div>';
            html += '<div class="searched-meta-date meta-date">' + result.datetime + '</div>';
            html += '</div>';
            
            html += '</div>';
            
            html += '<div class="searched-title title">';
            html += '<a href="' + result.url + '">' + result.title + '</a>';
            html += '</div>';
            
            html += '<div class="searched-link link">';
            html += '<a href="' + result.url + '">Read More »</a>';
            html += '</div>';
            
            html += '</div>';

            $('.searched-cards').append(html);
            lastIndex++;
          });

          if (lastIndex >= data.results.length) {
            $('.searched-more-btn').text('All Results Loaded').prop('disabled', true).hide();
          }
        });
      } else {
        console.log(data.results.length)
        $('.searched-more-btn').text('All Results Loaded').prop('disabled', true).hide();
      }
      } else {
        $('.searched-cards').html('<p style="color:#f00;font-size:17px;">Failed to fetch.</p>')
      }
    },
    error: function() {
      $('.searched-cards').html('<p style="color:#f00;font-size:17px;">Failed to load.</p>')
    }
  })
})