document.addEventListener("DOMContentLoaded", () => {
  const menuContainer = document.querySelector(".menu-container");

  fetch("data/food.json")
    .then((response) => response.json())
    .then((menuData) => {
      console.log(menuData);
      menuData.forEach((item) => {
        const cardHTML = `
          <div class="item-card">
            <div class = "left">
                <div class="item-info">
                    <p class="food-title">${item.name}</p>
                    <p>${item.category}</p>
                    <p>${item.cuisine}</p>
                </div>
                <p class="description">${item.description}</p>
                <p><strong>Ingredients:</strong> ${item.ingredients.join(
                  ", "
                )}</p>
                <p class="price">$${item.price.toFixed(2)}</p>
            </div>
            <div class="right">
                <img src="resources/${item.image}" alt="A plate of ${
          item.name
        }" />
            </div>
          </div>
        `;
        menuContainer.innerHTML += cardHTML;
      });
    })
    .catch((error) => {
      console.error("Error fetching menu data:", error);
      menuContainer.innerHTML = "<p>Sorry, we couldn't load the menu.</p>";
    });
});
