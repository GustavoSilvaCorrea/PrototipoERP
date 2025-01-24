<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <?php include "iconpage.php" ?>
</head>
<style>
    /* Global Styles */

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f9f9f9;
    }

    /* Header Styles */

    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1002;
        background-color: #333;
        color: #fff;
        padding: 1em;
        text-align: center;
    }

    /* Main Styles */

    main {
        display: flex;
        flex-direction: center;
        align-content: center;
        align-items: center;
        padding: 2em;
        margin-top: 40px;
    }

    #link {
        display: flex;
        justify-content: center;
        text-decoration: none;
        margin: 10px 0;
        font-size: 16px;
        font-weight: 500;
        color: white;
    }

    h1,
    h2 {
        margin-top: 0;
    }


    /* Responsive Styles */

    /* Mobile Devices (max-width: 480px) */

    @media only screen and (max-width: 480px) {
        header nav ul {
            flex-direction: column;
        }

        header nav li {
            margin-right: 0;
        }

        main {
            flex-direction: row;
            flex-wrap: wrap;
        }

    }

    /* Tablets (max-width: 768px) */

    @media only screen and (max-width: 768px) {
        main {
            flex-direction: row;
            flex-wrap: wrap;
        }
    }

    /* Desktops (min-width: 1024px) */

    @media only screen and (min-width: 1024px) {
        main {
            flex-direction: row;
            flex-wrap: wrap;
        }
    }
</style>

<body>
    <header>
        <h1>Estoque</h1>
    </header>
    <main>
        <h2>VSF JOÃO</h2>
    </main>
</body>

</html>