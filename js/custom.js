(function ($, Drupal) {
  Drupal.behaviors.worldTimeModule = {
    attach: function (context, settings) {
      function dateFormat(tz) {
        let selected_tz = new Date().toLocaleString("en-US", { timeZone: tz });
        let date_obj = new Date(selected_tz);
        let current_time = date_obj.toLocaleTimeString([], {'hour': '2-digit', 'minute': '2-digit', 'second': '2-digit'});
        let day = date_obj.toLocaleDateString("en-US", {weekday: 'long'});
        let date = ("0" + date_obj.getDate()).slice(-2);
        let month = date_obj.toLocaleString("en-US", {month: 'long'});
        let year = date_obj.getFullYear();
        let date_time = day + ", " + date + " " + month + " " + year
        return {"current_time":current_time, "date_time":date_time};
      }

      function display_ct(tz) {
        let dt = dateFormat(tz);
        document.getElementById('time').innerHTML = dt.current_time;
        document.getElementById('date').innerHTML = dt.date_time+" "+"("+tz+")";
        display_c(tz);
      }

      function display_c(tz){
        var refresh = 1000; // Refresh rate in milli seconds
        mytime = setTimeout(display_ct,refresh, tz);
      }
      
      let data = settings.timezone;
      if(data) {
        display_ct(data);   
      }

    }
  };
})(jQuery, Drupal);


  