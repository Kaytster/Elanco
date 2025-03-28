<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E6D9F4;
        }
        .header {
            background-color: #2C6AC2;
            height: 75px;
            display: flex;
            align-items: center;
            padding: 0 15px;
        }
        .header p {
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin-left: auto;
        }
        .header img {
            width: 100px;
        }
        .col-md-4 {
            margin-top: 50px;
            display: flex;
            justify-content: center;
        }

        /* Flip Card Styling */
        .flip-card {
            background-color: transparent;
            width: 350px;
            height: 450px;
            perspective: 1000px;
            font-family: sans-serif;
        }
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s ease-in-out;
            transform-style: preserve-3d;
        }
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-front, .flip-card-back {
            box-shadow: 0 8px 14px 0 rgba(0,0,0,0.2);
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 15px;
        }

        /* Front Side - Full Image with Effect */
        .flip-card-front {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .flip-card-front img {
            width: 100%;
            height: 100%;
            object-fit: cover;  /* Ensures the image covers the area */
            object-position: center center;  /* Focus the image on the top (face usually) */
            border-radius: 15px;
            transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
        }
        .flip-card:hover .flip-card-front img {
            transform: scale(1.1);  /* Zoom effect on hover */
            opacity: 0.9;  /* Slight transparency on hover */
        }

        /* Back Side - Transparent Image with Pet Info */
        .flip-card-back {
            background: linear-gradient(120deg, rgb(255, 174, 145) 30%, coral 88%, bisque 40%);
            color: white;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        
        /* Transparent Background Image on Back (Set manually for each pet) */
        .flip-card-back::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.3; /* Adjust transparency */
            filter: blur(4px); /* Add a slight blur for readability */
            border-radius: 15px;
        }

        /* Pet Name & Info */
        .flip-card-back h5 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }
        .flip-card-back p {
            font-size: 18px;
            margin: 5px 0;
            position: relative;
            z-index: 2;
        }
        .flip-card-back a {
            background-color: white;
            color: coral;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s ease-in-out;
            position: relative;
            z-index: 2;
        }
        .flip-card-back a:hover {
            background-color: coral;
            color: white;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="Elanco.png" alt="Elanco">
        <p>Hello, Pet Owner</p>
    </div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <?php
            $pets = [
                ["name" => "Snoopy", "age" => 13, "type" => "Siberian Husky", "image" => "siberian husky dog.jpg", "page" => "snoopy.php", "back_image" => "siberian husky dog.jpg"],
                ["name" => "Charlie", "age" => 8, "type" => "Golden Retriever", "image" => "Golden Retriever.jpg", "page" => "charlie.php", "back_image" => "Golden Retriever.jpg"],
                ["name" => "Teddy", "age" => 3, "type" => "Beagle", "image" => "Beagle Dog.jpg", "page" => "teddy.php", "back_image" => "Beagle Dog.jpg"]
            ];

            foreach ($pets as $pet) {
                echo "
                <div class='col-md-4'>
                    <div class='flip-card'>
                        <div class='flip-card-inner'>
                            <!-- Front Side -->
                            <div class='flip-card-front'>
                                <img src='{$pet['image']}' alt='{$pet['name']}'>
                            </div>
                            <!-- Back Side -->
                            <div class='flip-card-back' style='background-image: url(\"{$pet['back_image']}\");'>
                                <h5>{$pet['name']}</h5>
                                <p><strong>Age:</strong> {$pet['age']} years</p>
                                <p><strong>Breed:</strong> {$pet['type']}</p>
                                <a href='{$pet['page']}'>Read More</a>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>
