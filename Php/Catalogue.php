

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="Panier.css">
    <link rel="stylesheet" href="../Style/styleNavigation.css">
    <script type="text/javascript" src="Panier.js"></script>
</head>
<body>
<header>
    <a id="Base" href="#home">
        <img class="logo" src="../img/HatIcon.png" alt="Hat">
    </a>
    <div class="toggle" id="toggle"></div>

    <ul class="navigation">

        <li><a href="index.html" class="active">Accueil</a></li>
        <li><a href="Catalogue.php">Catalogue</a></li>
        <li><a href="Technique.html">Technique</a></li>
        <li><a href="Contact.html">Contact</a></li>
    </ul>
</header>

<div class="container">
    <h1>Hat Café Le Catalogue</h1>
    <p>LE CAFE </p>
    <p>Click 'Empty Cart' button to remove session cookies from browser.</p>
    <div id="alerts"></div>

    <?php include("Produit.php") ?>

    <div class="cart-container">
        <h2>Cart</h2>
        <table>
            <thead>
            <tr>
                <th><strong>Product</strong></th>
                <th><strong>Price</strong></th>
            </tr>
            </thead>
            <tbody id="carttable">
            </tbody>
        </table>
        <hr>
        <table id="carttotals">
            <tr>
                <td><strong>Items</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            <tr>
                <td>x <span id="itemsquantity">0</span></td>

                <td>$<span id="total">0</span></td>
            </tr>
        </table>


        <div class="cart-buttons">
            <button id="emptycart">Empty Cart</button>
            <button id="checkout">Checkout</button>
        </div>
    </div>
</div>




<script>
    /* get cart total from session on load */
    updateCartTotal();

    /* button event listeners */
    document.getElementById("emptycart").addEventListener("click", emptyCart);
    var btns = document.getElementsByClassName('addtocart');
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener('click', function () {
            addToCart(this);
        });
    }

    /* ADD TO CART functions */

    function addToCart(elem) {
//init
        var sibs = [];
        var getprice;
        var getproductName;
        var cart = [];
        var stringCart;
//cycles siblings for product info near the add button
        while (elem = elem.previousSibling) {
            if (elem.nodeType === 3) continue; // text node
            if (elem.className == "price") {
                getprice = elem.innerText;
            }
            if (elem.className == "productname") {
                getproductName = elem.innerText;
            }
            sibs.push(elem);
        }
//create product object
        var product = {
            productname: getproductName,
            price: getprice
        };
//convert product data to JSON for storage
        var stringProduct = JSON.stringify(product);
        /*send product data to session storage */

        if (!sessionStorage.getItem('cart')) {
//append product JSON object to cart array
            cart.push(stringProduct);
//cart to JSON
            stringCart = JSON.stringify(cart);
//create session storage cart item
            sessionStorage.setItem('cart', stringCart);
            addedToCart(getproductName);
            updateCartTotal();
        } else {
//get existing cart data from storage and convert back into array
            cart = JSON.parse(sessionStorage.getItem('cart'));
//append new product JSON object
            cart.push(stringProduct);
//cart back to JSON
            stringCart = JSON.stringify(cart);
//overwrite cart data in sessionstorage
            sessionStorage.setItem('cart', stringCart);
            addedToCart(getproductName);
            updateCartTotal();
        }
    }

    /* Calculate Cart Total */
    function updateCartTotal() {
//init
        var total = 0;
        var price = 0;
        var items = 0;
        var productname = "";
        var carttable = "";
        if (sessionStorage.getItem('cart')) {
//get cart data & parse to array
            var cart = JSON.parse(sessionStorage.getItem('cart'));
//get no of items in cart
            items = cart.length;
//loop over cart array
            for (var i = 0; i < items; i++) {
//convert each JSON product in array back into object
                var x = JSON.parse(cart[i]);
//get property value of price
                price = parseFloat(x.price.split('$')[1]);
                productname = x.productname;
//add price to total
                carttable += "<tr><td>" + productname + "</td><td>$" + price.toFixed(2) + "</td></tr>";
                total += price;
            }

        }
//update total on website HTML
        document.getElementById("total").innerHTML = total.toFixed(2);
//insert saved products to cart table
        document.getElementById("carttable").innerHTML = carttable;
//update items in cart on website HTML
        document.getElementById("itemsquantity").innerHTML = items;
    }

    //user feedback on successful add
    function addedToCart(pname) {
        var message = pname + " was added to the cart";
        var alerts = document.getElementById("alerts");
        alerts.innerHTML = message;
        if (!alerts.classList.contains("message")) {
            alerts.classList.add("message");
        }
    }

    /* User Manually empty cart */
    function emptyCart() {
//remove cart session storage object & refresh cart totals
        if (sessionStorage.getItem('cart')) {
            sessionStorage.removeItem('cart');
            updateCartTotal();
//clear message and remove class style
            var alerts = document.getElementById("alerts");
            alerts.innerHTML = "";
            if (alerts.classList.contains("message")) {
                alerts.classList.remove("message");
            }
        }
    } </script>
</body>
</html>