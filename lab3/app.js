document.addEventListener("DOMContentLoaded", function () {
  API_KEY = "";

  navigator.geolocation.getCurrentPosition(
    function (position) {
      let lat = position.coords.latitude;
      let lon = position.coords.longitude;
      let daily = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=imperial`;
      let weekly = `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=imperial`;
      let airquality = `https://air-quality-api.open-meteo.com/v1/air-quality?latitude=${lat}&longitude=${lon}&hourly=us_aqi,pm10,pm2_5,nitrogen_dioxide,ozone`;
      fetch(daily)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          let weather = document.querySelector(".left");
          const iconCode =
            data.weather && data.weather[0] && data.weather[0].icon;
          const desc =
            data.weather && data.weather[0] && data.weather[0].description;
          const iconBase =
            "https://rodrigokamada.github.io/openweathermap/images/";
          const iconFile = iconCode ? `${iconCode}_t.png` : null;
          const icon2x = iconCode ? `${iconCode}_t@2x.png` : null;
          const icon4x = iconCode ? `${iconCode}_t@4x.png` : null;
          // Fallback to OpenWeatherMap's own icon CDN if the external repo is unavailable
          const fallback = iconCode
            ? `https://openweathermap.org/img/wn/${iconCode}@2x.png`
            : "";

          const imgHtml = iconFile
            ? `<img class="weather-icon" src="${iconBase + iconFile}"
               srcset="${iconBase + icon2x} 2x, ${iconBase + icon4x} 4x"
               alt="${desc || "weather icon"}"
               onerror="this.onerror=null; this.src='${fallback}'; this.srcset='';" />`
            : "";

          weather.insertAdjacentHTML(
            "afterbegin",
            `<div class="current-weather-card">
              <div class="weather-header">
                ${imgHtml}
                <div class="weather-headline">
                  <h3 class="header">${data.name}</h3>
                  <p class="current-temp">${data.main.temp} °F</p>
                  <p>Feels Like: ${data.main.feels_like} °F</p>
                </div>
              </div>
            </div>`
          );
        });
      fetch(weekly)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          const weekly_info = document.querySelector(".forecast-container");

          const dailyData = {};

          data.list.forEach((entry) => {
            const date = new Date(entry.dt * 1000);
            const dayKey = date.toISOString().split("T")[0];

            if (!dailyData[dayKey]) {
              dailyData[dayKey] = [];
            }
            dailyData[dayKey].push(entry);
          });

          const dayKeys = Object.keys(dailyData).slice(0, 5);

          dayKeys.forEach((day) => {
            const dayEntries = dailyData[day];

            const temps = dayEntries.map((e) => e.main.temp);
            const high = Math.max(...temps);
            const low = Math.min(...temps);

            const iconEntry = dayEntries[Math.floor(dayEntries.length / 2)];
            const iconCode =
              iconEntry.weather &&
              iconEntry.weather[0] &&
              iconEntry.weather[0].icon;
            const desc =
              iconEntry.weather &&
              iconEntry.weather[0] &&
              iconEntry.weather[0].description;

            const iconBase =
              "https://rodrigokamada.github.io/openweathermap/images/";
            const iconFile = iconCode ? `${iconCode}_t.png` : null;
            const icon2x = iconCode ? `${iconCode}_t@2x.png` : null;
            const icon4x = iconCode ? `${iconCode}_t@4x.png` : null;
            const fallback = iconCode
              ? `https://openweathermap.org/img/wn/${iconCode}@2x.png`
              : "";

            const imgHtml = iconFile
              ? `<img class="weather-icon" src="${iconBase + iconFile}"
             srcset="${iconBase + icon2x} 2x, ${iconBase + icon4x} 4x"
             alt="${desc || "weather icon"}"
             onerror="this.onerror=null; this.src='${fallback}'; this.srcset='';" />`
              : "";

            const dayName = new Date(day).toLocaleDateString(undefined, {
              weekday: "long",
            });

            weekly_info.innerHTML += `
              <p class="day">${dayName}</p>
              ${imgHtml}
              <p>High: ${high.toFixed(1)} °F</p>
              <p>Low: ${low.toFixed(1)} °F</p>
            `;
          });
        })
        .catch((err) => console.error("Error fetching forecast:", err));

      fetch(airquality)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          let airquality = document.querySelector(".air-quality-container");
          if (data.hourly && data.hourly.us_aqi) {
            const filteredData = data.hourly.us_aqi.filter(
              (value) => value !== null
            );
            const us_aqi = filteredData.slice(-1)[0];
            airquality.insertAdjacentHTML(
              "afterbegin",
              `<h3 class="center-header">Air Quality</h3>
              <p><strong>Air Quality Index:</strong> ${us_aqi} µg/m³</p>`
            );
          }
          if (data.hourly && data.hourly.pm2_5) {
            const filteredData = data.hourly.pm2_5.filter(
              (value) => value !== null
            );
            const pm2_5 = filteredData.slice(-1)[0];
            airquality.insertAdjacentHTML(
              "beforeend",
              `<p><strong>PM2.5:</strong> ${pm2_5} µg/m³</p>`
            );
          }
          if (data.hourly && data.hourly.pm10) {
            const filteredData = data.hourly.pm10.filter(
              (value) => value !== null
            );
            const pm10 = filteredData.slice(-1)[0];
            airquality.insertAdjacentHTML(
              "beforeend",
              `<p><strong>PM10:</strong> ${pm10} µg/m³</p>`
            );
          }
          if (data.hourly && data.hourly.nitrogen_dioxide) {
            const filteredData = data.hourly.nitrogen_dioxide.filter(
              (value) => value !== null
            );
            const nitrogen_dioxide = filteredData.slice(-1)[0];
            airquality.insertAdjacentHTML(
              "beforeend",
              `<p><strong>Nitrogen Dioxide:</strong> ${nitrogen_dioxide} µg/m³</p>`
            );
          }
          if (data.hourly && data.hourly.ozone) {
            const filteredData = data.hourly.ozone.filter(
              (value) => value !== null
            );
            const ozone = filteredData.slice(-1)[0];
            airquality.insertAdjacentHTML(
              "beforeend",
              `<p><strong>Ozone:</strong> ${ozone} µg/m³</p>`
            );
          }
          airquality.insertAdjacentHTML(
            "beforeend",
            `<div class="air-quality-info">
                <h3 class="center-header">Air Quality Metrics</h3>
                <details>
                  <summary>Air Quality Index (AQI)</summary>
                  <p>The AQI summarizes overall air pollution: higher values indicate worse air quality and greater health concern.</p>
                </details>
                <details>
                  <summary>PM2.5</summary>
                  <p>Fine Particulate Matter  are inhalable pollutant particles with a diameter less than 2.5 micrometers that can enter the lungs and bloodstream, resulting in serious health issues. The most severe impacts are on the lungs and heart. Exposure can result in coughing or difficulty breathing, aggravated asthma, and the development of chronic respiratory disease.</p>
                </details>
                <details>
                  <summary>PM10</summary>
                  <p>Particulate Matter are inhalable pollutant particles with a diameter less than 10 micrometers. Particles that are larger than 2.5 micrometers can be deposited in airways, resulting in health issues. Exposure can result in eye and throat irritation, coughing or difficulty breathing, and aggravated asthma. More frequent and excessive exposure can result in more serious health effects.</p>
                </details>
                <details>
                  <summary>NO / NO2 (Nitrogen oxides)</summary>
                  <p>Breathing in high levels of Nitrogen Dioxide increases the risk of respiratory problems. Coughing and difficulty breathing are common and more serious health issues such as respiratory infections can occur with longer exposure.</p>
                </details>
                <details>
                  <summary>O3 (Ozone)</summary>
                  <p>Ground-level Ozone can aggravate existing respiratory diseases and also lead to throat irritation, headaches, and chest pain.</p>
                </details>
              </div>`
          );
        });
    },
    function (error) {
      console.error("Error Code = " + error.code + " - " + error.message);
    }
  );
});
