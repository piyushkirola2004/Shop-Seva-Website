document.addEventListener("DOMContentLoaded", () => {
    // ============ Sidebar Menu with Slide Animation & Styled ============
    const menuBtn = document.querySelector(".menuBar a"); // "All" button
    if (menuBtn) {
        menuBtn.addEventListener("click", (e) => {
            e.preventDefault();
            let sidebar = document.querySelector(".sidebar");
            if (!sidebar) {
                sidebar = document.createElement("div");
                sidebar.className = "sidebar";
                sidebar.innerHTML = `
          <div class="sidebar-content">
            <h2>All Categories</h2>
            <ul>
              <li>Mobiles</li>
              <li>Electronics</li>
              <li>Fashion</li>
              <li>Home & Kitchen</li>
              <li>Sports</li>
              <li>Health & Fitness</li>
              <li>Beauty & Personal Care</li>
              <li>Books & Stationary</li>
              <li>Toys & Baby Products</li>
              <li>Grocery & Gourmet</li>
              <li>Musical Instruments & Equipment</li>
              <li>Office & School Supplies</li>
              <li>Movies, Music & Games</li>
              <li>Pet Supplies</li>
              <li>Computer & Software</li>
              <li>Sports & Outdoors</li>
            </ul>
            <button id="closeSidebar">Close</button>
          </div>
        `;
                sidebar.style.position = "fixed";
                sidebar.style.top = "0";
                sidebar.style.left = "-300px";
                sidebar.style.width = "300px";
                sidebar.style.height = "100%";
                sidebar.style.backgroundColor = "#fff";
                sidebar.style.boxShadow = "2px 0 10px rgba(0,0,0,0.2)";
                sidebar.style.transition = "left 0.4s ease";
                sidebar.style.zIndex = "1000";
                document.body.appendChild(sidebar);

                // Style content
                const sidebarContent = sidebar.querySelector(".sidebar-content");
                sidebarContent.style.color = "#000";
                sidebarContent.style.padding = "20px";
                sidebarContent.style.fontFamily = "Arial, sans-serif";

                // List hover effects
                const lis = sidebarContent.querySelectorAll("li");
                lis.forEach(li => {
                    li.style.cursor = "pointer";
                    li.style.margin = "5px 0";
                    li.addEventListener("mouseenter", () => li.style.color = "#ff9900");
                    li.addEventListener("mouseleave", () => li.style.color = "#000");
                });

                // Slide in
                setTimeout(() => {
                    sidebar.style.left = "0";
                }, 10);

                // Close button with slide-out
                document.querySelector("#closeSidebar").addEventListener("click", () => {
                    sidebar.style.left = "-300px";
                    setTimeout(() => sidebar.remove(), 400);
                });
            }
        });
    }

    // ============ Search Function ============
    const searchBtn = document.querySelector(".search_box .search");
    const searchInput = document.querySelector(".search_box input");
    const products = document.querySelectorAll(".product");
    if (searchBtn && searchInput) {
        searchBtn.addEventListener("click", () => {
            const query = searchInput.value.trim().toLowerCase();
            if (query) {
                products.forEach(prod => {
                    const title = prod.querySelector("h3")?.textContent.toLowerCase();
                    if (title && title.includes(query)) {
                        prod.style.display = "block";
                    } else {
                        prod.style.display = "none";
                    }
                });
            } else {
                alert("Please enter a search term.");
                products.forEach(p => (p.style.display = "block"));
            }
        });
    }

    // ============ Cart System with Fly-to-Cart ============
    const cartIcon = document.querySelector(".cart");
    let cart = [];
    const cartCounter = document.createElement("span");
    cartCounter.style.color = "yellow";
    cartCounter.style.fontWeight = "bold";
    cartCounter.textContent = 0;
    if (cartIcon) cartIcon.appendChild(cartCounter);

    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    addToCartButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const product = btn.closest(".product");
            const name = product.querySelector("h3").textContent;
            const price = parseFloat(product.dataset.price);
            cart.push({ name, price });
            cartCounter.textContent = cart.length;

            // Fly-to-cart animation
            const productImg = product.querySelector("img");
            if (productImg) {
                const imgClone = productImg.cloneNode(true);
                const imgRect = productImg.getBoundingClientRect();
                imgClone.style.position = "fixed";
                imgClone.style.top = imgRect.top + "px";
                imgClone.style.left = imgRect.left + "px";
                imgClone.style.width = productImg.offsetWidth + "px";
                imgClone.style.transition = "all 0.7s ease-in-out";
                imgClone.style.zIndex = "1000";
                document.body.appendChild(imgClone);

                const cartRect = cartIcon.getBoundingClientRect();
                setTimeout(() => {
                    imgClone.style.top = cartRect.top + "px";
                    imgClone.style.left = cartRect.left + "px";
                    imgClone.style.width = "40px";
                    imgClone.style.opacity = "0";
                }, 10);
                setTimeout(() => imgClone.remove(), 700);
            }

            // Button bounce
            btn.style.transform = "scale(1.2)";
            setTimeout(() => btn.style.transform = "scale(1)", 150);

            alert(`${name} added to cart! Total items: ${cart.length}`);
        });
    });

    if (cartIcon) {
        cartIcon.addEventListener("dblclick", () => {
            let total = cart.reduce((sum, item) => sum + item.price, 0);
            alert(`Cart Items:\n${cart.map(i => i.name).join(", ")}\nTotal: $${total.toFixed(2)}`);
        });
    }

    // ============ Image Slider with Fade ============
    const sliders = document.querySelectorAll(".product-slider");
    sliders.forEach(slider => {
        const images = slider.querySelectorAll("img");
        let current = 0;
        const nextBtn = document.createElement("button");
        nextBtn.textContent = ">";
        const prevBtn = document.createElement("button");
        prevBtn.textContent = "<";
        slider.appendChild(prevBtn);
        slider.appendChild(nextBtn);

        images.forEach((img, i) => {
            img.style.display = i === 0 ? "block" : "none";
            img.style.transition = "opacity 0.5s ease";
        });

        const showImage = (index) => {
            images.forEach((img, i) => {
                img.style.opacity = "0";
                img.style.display = "none";
            });
            images[index].style.display = "block";
            setTimeout(() => images[index].style.opacity = "1", 10);
        };

        nextBtn.addEventListener("click", () => {
            current = (current + 1) % images.length;
            showImage(current);
        });
        prevBtn.addEventListener("click", () => {
            current = (current - 1 + images.length) % images.length;
            showImage(current);
        });
    });

    // ============ Orders & Return ============
    const orderBtn = document.querySelector(".place-order");
    const returnBtn = document.querySelector(".return-order");
    if (orderBtn) {
        orderBtn.addEventListener("click", () => {
            if (cart.length === 0) return alert("Cart is empty!");
            orderBtn.style.transform = "scale(1.1)";
            setTimeout(() => orderBtn.style.transform = "scale(1)", 150);
            alert(`Order placed for:\n${cart.map(i => i.name).join(", ")}\nTotal: $${cart.reduce((s, i) => s + i.price, 0).toFixed(2)}`);
            cart = [];
            cartCounter.textContent = 0;
        });
    }
    if (returnBtn) {
        returnBtn.addEventListener("click", () => {
            const returned = prompt("Enter product name to return:");
            const index = cart.findIndex(i => i.name.toLowerCase() === returned?.toLowerCase());
            if (index > -1) {
                const item = cart.splice(index, 1)[0];
                alert(`${item.name} returned successfully!`);
                cartCounter.textContent = cart.length;
            } else {
                alert("Product not found in cart!");
            }
        });
    }

    // ============ Checkout / Address ============
    const checkoutBtn = document.querySelector(".checkout");
    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", () => {
            const name = prompt("Enter your name:");
            const address = prompt("Enter shipping address:");
            if (name && address) {
                alert(`Order for ${name} will be shipped to:\n${address}`);
            } else {
                alert("Please enter valid details.");
            }
        });
    }

    // ============ Login popup with registration toggle ============
    const accountInfo = document.querySelector(".ac_info");
    if (accountInfo) {
        accountInfo.addEventListener("click", () => {
            // Create login popup
            const popup = document.createElement("div");
            popup.className = "login-popup";
            popup.innerHTML = `
            <div class="popup-content">
                <h2>Sign In</h2>
                <form id="loginForm" action="login.php" method="POST">
                    <input type="text" name="email" placeholder="Email or Mobile number" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <p>New user? <a href="#" id="registerlink">Create account</a></p>
            </div>
        `;

            popup.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); display: flex;
            align-items: center; justify-content: center; z-index: 1000;
        `;
            document.body.appendChild(popup);

            const content = popup.querySelector(".popup-content");
            content.style.transform = "scale(0)";
            content.style.transition = "transform 0.3s ease";
            setTimeout(() => content.style.transform = "scale(1)", 10);

            popup.addEventListener("click", (e) => {
                if (e.target === popup) popup.remove();
            });

            // ===== Registration link inside login popup =====
            const registerLink = popup.querySelector("#registerlink");
            if (registerLink) {
                registerLink.addEventListener("click", (e) => {
                    e.preventDefault();
                    // Replace login form with registration form in the same popup
                    content.innerHTML = `
                    <h2>Create Account</h2>
                    <form id="registerForm" action="register.php" method="POST">
                        <input type="text" name="username" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Register</button>
                    </form>
                `;
                });
            }
        });
    }


    // ============ Scroll Reveal Products ============
    const revealProducts = () => {
        const triggerBottom = window.innerHeight / 5 * 4;
        products.forEach(prod => {
            const prodTop = prod.getBoundingClientRect().top;
            if (prodTop < triggerBottom) {
                prod.style.opacity = "1";
                prod.style.transform = "translateY(0)";
                prod.style.transition = "all 0.5s ease-out";
            } else {
                prod.style.opacity = "0";
                prod.style.transform = "translateY(50px)";
            }
        });
    };
    window.addEventListener("scroll", revealProducts);
    revealProducts();

    // ============ Back to Top ============
    const backTopBtn = document.querySelector(".backTop a");
    if (backTopBtn) {
        backTopBtn.addEventListener("click", (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
}); 