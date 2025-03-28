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
        }
        @keyframes shake-animation 
        {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(2deg); }
            50% { transform: rotate(-2deg); }
            75% { transform: rotate(2deg); }
            100% { transform: rotate(0deg); }
        }
        .header p 
        {
            color: white;
            padding: 15px;
            float: right;
            font-size: 24px;
            font-weight: bold;
            animation: shake-animation 4.72s ease infinite;
            transform-origin: 50% 50%;
        }
        .header img 
        {
            width: 100px;
            text-align: end;
            margin-top: 10px;
            margin-left: 10px;
            
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
    
        a
        {
            text-decoration: none;
            color: white;
            font-size: 20px;

        }
        .zoom-hover-text
        {
            background-color: #2C6AC2;
            border-radius: 5px;
            padding: 5px;
            text-align: center; 
            display: inline-block;
            transition: transform 0.3s ease, opacity 0.3s ease;
            cursor: pointer;
        }
        .zoom-hover-text:hover 
        {
            transform: scale(1.2) translateY(-5px);
            opacity: 0.8;
        }
        .col-md-4
        {
            margin-top: 100px;
        }
        
    </style>

</head>
<body>
    
        
        </div>
    <div class="header">
        <a href="dashboard.php"><img src="Elanco.png" alt="Elanco"></a>
        <p>Hello, Pet Owner</p>
    </div>

    <div class="container mt-4">
        <div class="row">
            <?php
            $pets = [
                ["name" => "Snoopy", "age" => 13, "type" => "Siberian Husky", "image" => "siberian husky dog.jpg", "page" => "snoopy.php"],
                ["name" => "Charlie", "age" => 8, "type" => "Golden Retriever", "image" => "Golden Retriever.jpg", "page" => "charlie.php"],
                ["name" => "Teddy", "age" => 3, "type" => "Beagle", "image" => "Beagle Dog.jpg", "page" => "teddy.php"]
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
                            <a href='{$pet['page']}' class='zoom-hover-text'>Read More</a>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>
