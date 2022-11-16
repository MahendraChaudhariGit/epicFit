function getNotify() {
  if (!public_url)
    var public_url = $('meta[name="public_url"]').attr("content");

  $.ajax({
    url: public_url + "dashboard/notification",
    method: "get",
    data: {},
    success: function (data) {
      var data = JSON.parse(data);
      html = "";
      $.each(data, function (index, value) {
        
        html +=
          '<div class="row m-b-20 text" style="margin-left:1px;">' + value.class_name+'<span style="margin-left:2px;">'+ value.class_date+ '</span><span style="margin-left:2px;">'+ value.class_time+ '</span>';
          
       
        html += "<div><span class='time'>" + value.text + "</span>";
        if (value.performed_by != "null" && value.performed_by != undefined) {
          html +=
            '<span class="performed_by" style="margin-left:7px;">By-' +
            value.performed_by +
            "</span>";
        }
        html += "</div><div><span class='time' style='color: #1c23f3 !important;'>" + value.time + "</span></div></div>";
      });

      // notifyObj=JSON.parse(data);
      $("#notification").append(html);
    },
  });
}
