<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="new.css">
    <?php include 'navbar.php';?>
    <style>
        /* Main container for the page */
        .main-container {
            padding: 20px 0;
            background-color: var(--background);
            min-height: 100vh;
        }
        
        /* Page title styles */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 20px;
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient);
            border-radius: 10px;
        }
        
        .page-title {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* Pet card container */
        .pet-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
        }
        
        /* Pet card styling */
        .pet-card {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 350px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .pet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 103, 177, 0.2);
        }
        
        .pet-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .pet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .pet-card:hover .pet-image img {
            transform: scale(1.1);
        }
        
        .pet-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(0, 103, 177, 0.25);
        }
        
        .pet-info {
            padding: 25px;
        }
        
        .pet-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .pet-type {
            color: var(--text-secondary);
            font-size: 1rem;
            margin-bottom: 15px;
        }
        
        .pet-stats {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .pet-stat {
            text-align: center;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Pet action buttons */
        .pet-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        
        /* Media queries for responsiveness */
        @media (max-width: 1200px) {
            .pet-card {
                width: 320px;
            }
        }
        
        @media (max-width: 992px) {
            .pet-card-container {
                gap: 20px;
            }
            
            .pet-card {
                width: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .pet-card-container {
                flex-wrap: wrap;
            }
            
            .pet-card {
                width: 100%;
                max-width: 450px;
            }
            
            .page-title {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 576px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .pet-info {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Pet Health Tracker</h1>
                <p class="page-subtitle">Monitor and track your pet's health parameters in real-time to ensure they live a happy and healthy life. Choose your pet to view their detailed health dashboard.</p>
            </div>

            <div class="pet-card-container">
                <?php
                $pets = [
                    ["name" => "Snoopy", "age" => 13, "type" => "Siberian Husky", "image" => "siberian husky dog.jpg", "page" => "dashboard.php", "weight" => "25 kg", "health" => "Good"],
                    ["name" => "Charlie", "age" => 8, "type" => "Golden Retriever", "image" => "Golden Retriever.jpg", "page" => "dashboard.php", "weight" => "32 kg", "health" => "Excellent"],
                    ["name" => "Teddy", "age" => 3, "type" => "Beagle", "image" => "Beagle Dog.jpg", "page" => "dashboard.php", "weight" => "10 kg", "health" => "Good"]
                ];

                foreach ($pets as $pet) {
                    $healthClass = strtolower($pet['health']) === 'excellent' ? 'pet-health-excellent' : 'pet-health-good';
                ?>
                    <div class="pet-card">
                        <div class="pet-image">
                            <img src="<?php echo $pet['image']; ?>" alt="<?php echo $pet['name']; ?>">
                            <div class="pet-badge"><?php echo $pet['health']; ?></div>
                        </div>
                        <div class="pet-info">
                            <h2 class="pet-name"><?php echo $pet['name']; ?></h2>
                            <p class="pet-type"><?php echo $pet['type']; ?></p>
                            
                            <div class="pet-stats">
                                <div class="pet-stat">
                                    <div class="stat-label">Age</div>
                                    <div class="stat-value"><?php echo $pet['age']; ?> yrs</div>
                                </div>
                                
                                <div class="pet-stat">
                                    <div class="stat-label">Weight</div>
                                    <div class="stat-value"><?php echo $pet['weight']; ?></div>
                                </div>
                                
                                <div class="pet-stat">
                                    <div class="stat-label">Health</div>
                                    <div class="stat-value"><?php echo $pet['health']; ?></div>
                                </div>
                            </div>
                            
                            <div class="pet-actions">
                                <a href="dashboard.php?pet_id=<?php echo urlencode($pet['name']); ?>" class="ui-button">
                                    <span><i class="fas fa-tachometer-alt"></i> View Dashboard</span>
                                </a>
                                <a href="trends.php?dog_id=CANINE<?php echo str_pad(array_search($pet, $pets) + 1, 3, '0', STR_PAD_LEFT); ?>" class="ui-button secondary">
                                    <span><i class="fas fa-chart-line"></i> View Trends</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            
            <div class="scroll-indicator">
                <i class="fas fa-arrows-left-right"></i> Scroll to see all pets
            </div>
        </div>
    </div>
</body>
</html>
