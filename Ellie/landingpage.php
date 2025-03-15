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
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 180px;
            object-fit: cover;
        }
        .card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <div class="header">Hello, Pet Owner</div>

    <div class="container mt-4">
        <div class="row">
            <?php
            $pets = [
                ["name" => "Snoopy", "age" => 13, "type" => "Siberian Husky", "image" => "https://source.unsplash.com/300x200/?husky", "page" => "snoopy.php"],
                ["name" => "Charlie", "age" => 8, "type" => "Golden Retriever", "image" => "https://source.unsplash.com/300x200/?goldenretriever", "page" => "charlie.php"],
                ["name" => "Teddy", "age" => 3, "type" => "Beagle", "image" => "https://source.unsplash.com/300x200/?beagle", "page" => "teddy.php"]
            ];

            foreach ($pets as $pet) {
                echo "
                <div class='col-md-4'>
                    <div class='card text-center p-3'>
                        <img src='{$pet['image']}' class='card-img-top' alt='{$pet['name']}'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$pet['name']}</h5>
                            <p class='card-text'><strong>Age:</strong> {$pet['age']}</p>
                            <p class='card-text'><strong>Type:</strong> {$pet['type']}</p>
                            <a href='{$pet['page']}' class='btn btn-primary'>Read More</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>
