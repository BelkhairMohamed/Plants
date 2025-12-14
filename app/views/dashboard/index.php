<?php
$pageTitle = 'Tableau de bord';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container">
    <h1><i class="fas fa-chart-line"></i> Tableau de bord</h1>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #2d8659, #4a9d7c);">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalPlants; ?></h3>
                <p>Plantes totales</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ff8787);">
                <i class="fas fa-tint"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($plantsNeedingWater); ?></h3>
                <p>À arroser</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ffd93d, #ffed4e);">
                <i class="fas fa-flask"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($plantsNeedingFertilizer); ?></h3>
                <p>À fertiliser</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #51cf66, #69db7c);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo count($recentCareEvents); ?></h3>
                <p>Activités récentes</p>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Plantes par emplacement</h3>
            <canvas id="roomChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Plantes ajoutées (6 derniers mois)</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-chart-doughnut"></i> Types de soins (30 derniers jours)</h3>
            <canvas id="careTypeChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Activité quotidienne (30 derniers jours)</h3>
            <canvas id="dailyActivityChart"></canvas>
        </div>
    </div>
    
    <?php if ($weather): ?>
        <div class="weather-card">
            <h3><i class="fas fa-cloud-sun"></i> Météo - <?php echo htmlspecialchars($this->getCurrentUser()['city'] ?? 'Votre ville'); ?></h3>
            <p>Température: <?php echo $weather['temperature']; ?>°C</p>
            <p>Humidité: <?php echo $weather['humidity']; ?>%</p>
            <?php if (!empty($weatherRecommendations)): ?>
                <div class="recommendations">
                    <h4>Recommandations:</h4>
                    <ul>
                        <?php foreach ($weatherRecommendations as $rec): ?>
                            <li><?php echo htmlspecialchars($rec); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="dashboard-grid">
        <div class="dashboard-card urgent">
            <h2><i class="fas fa-tint"></i> Plantes à arroser aujourd'hui</h2>
            <?php if (empty($plantsNeedingWater)): ?>
                <p>Aucune plante ne nécessite d'arrosage pour le moment.</p>
            <?php else: ?>
                <ul class="plant-list">
                    <?php foreach ($plantsNeedingWater as $plant): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $plant['id']; ?>">
                                <?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2><i class="fas fa-flask"></i> Plantes à fertiliser</h2>
            <?php if (empty($plantsNeedingFertilizer)): ?>
                <p>Aucune fertilisation nécessaire pour le moment.</p>
            <?php else: ?>
                <ul class="plant-list">
                    <?php foreach ($plantsNeedingFertilizer as $plant): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $plant['id']; ?>">
                                <?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2><i class="fas fa-history"></i> Activité récente</h2>
            <?php if (empty($recentCareEvents)): ?>
                <p>Aucune activité récente.</p>
            <?php else: ?>
                <ul class="activity-list">
                    <?php foreach ($recentCareEvents as $event): ?>
                        <li>
                            <?php echo htmlspecialchars($event['event_type']); ?> - 
                            <?php echo htmlspecialchars($event['nickname_for_plant'] ?? $event['common_name']); ?>
                            <span class="date"><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2>👥 Communauté</h2>
            <?php if (empty($recentPosts)): ?>
                <p>Aucun post récent.</p>
            <?php else: ?>
                <ul class="post-preview">
                    <?php foreach ($recentPosts as $post): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars(substr($post['content_text'], 0, 100)); ?>...
                            </a>
                            <span class="author">par <?php echo htmlspecialchars($post['username']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Chart.js configuration with beautiful colors
const chartColors = {
    primary: '#2d8659',
    secondary: '#4a9d7c',
    accent: '#6ab89a',
    danger: '#ff6b6b',
    warning: '#ffd93d',
    success: '#51cf66',
    info: '#4dabf7',
    purple: '#9775fa',
    pink: '#f783ac',
    gradient1: ['#2d8659', '#4a9d7c', '#6ab89a'],
    gradient2: ['#ff6b6b', '#ff8787', '#ffa8a8'],
    gradient3: ['#ffd93d', '#ffed4e', '#fff176'],
    gradient4: ['#51cf66', '#69db7c', '#8ce99a']
};

// Get theme
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const textColor = isDark ? '#e9ecef' : '#333';
const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
const bgColor = isDark ? '#2d2d2d' : '#ffffff';

// Chart.js default configuration
Chart.defaults.color = textColor;
Chart.defaults.borderColor = gridColor;
Chart.defaults.backgroundColor = bgColor;

// Room Chart (Doughnut)
<?php
$roomLabels = [];
$roomData = [];
foreach ($plantsByRoom as $room) {
    $roomLabels[] = htmlspecialchars($room['room_location']);
    $roomData[] = (int)$room['count'];
}
?>
const roomCtx = document.getElementById('roomChart');
if (roomCtx && <?php echo json_encode(!empty($roomData)); ?>) {
    new Chart(roomCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($roomLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($roomData); ?>,
                backgroundColor: chartColors.gradient1,
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
} else if (roomCtx) {
    roomCtx.parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--text-color); opacity: 0.7;">Aucune donnée disponible</p>';
}

// Monthly Chart (Bar)
<?php
$monthLabels = [];
$monthData = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $date = date('Y-m', strtotime("-$i months"));
    $months[$date] = 0;
    $monthLabels[] = date('M Y', strtotime("-$i months"));
}
foreach ($plantsByMonth as $month) {
    if (isset($months[$month['month']])) {
        $months[$month['month']] = (int)$month['count'];
    }
}
$monthData = array_values($months);
?>
const monthlyCtx = document.getElementById('monthlyChart');
if (monthlyCtx) {
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($monthLabels); ?>,
            datasets: [{
                label: 'Plantes ajoutées',
                data: <?php echo json_encode($monthData); ?>,
                backgroundColor: chartColors.gradient1[0],
                borderColor: chartColors.gradient1[1],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor,
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 }
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
}

// Care Type Chart (Pie)
<?php
$careLabels = [];
$careData = [];
foreach ($careEventsByType as $care) {
    $careLabels[] = htmlspecialchars($care['event_type']);
    $careData[] = (int)$care['count'];
}
?>
const careTypeCtx = document.getElementById('careTypeChart');
if (careTypeCtx && <?php echo json_encode(!empty($careData)); ?>) {
    new Chart(careTypeCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($careLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($careData); ?>,
                backgroundColor: [
                    chartColors.primary,
                    chartColors.secondary,
                    chartColors.accent,
                    chartColors.success,
                    chartColors.info,
                    chartColors.warning,
                    chartColors.danger
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    cornerRadius: 8
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
} else if (careTypeCtx) {
    careTypeCtx.parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--text-color); opacity: 0.7;">Aucune donnée disponible</p>';
}

// Daily Activity Chart (Line)
<?php
$dailyLabels = [];
$dailyData = [];
$days = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $days[$date] = 0;
    $dailyLabels[] = date('d/m', strtotime("-$i days"));
}
foreach ($careEventsByDay as $day) {
    if (isset($days[$day['date']])) {
        $days[$day['date']] = (int)$day['count'];
    }
}
$dailyData = array_values($days);
?>
const dailyCtx = document.getElementById('dailyActivityChart');
if (dailyCtx) {
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dailyLabels); ?>,
            datasets: [{
                label: 'Soins effectués',
                data: <?php echo json_encode($dailyData); ?>,
                borderColor: chartColors.primary,
                backgroundColor: isDark ? 'rgba(45, 134, 89, 0.2)' : 'rgba(45, 134, 89, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: chartColors.primary,
                pointBorderColor: bgColor,
                pointBorderWidth: 2,
                pointHoverBackgroundColor: chartColors.secondary,
                pointHoverBorderColor: bgColor,
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12,
                    titleFont: { size: 14, weight: '600' },
                    bodyFont: { size: 13 },
                    borderColor: chartColors.primary,
                    borderWidth: 2,
                    cornerRadius: 8,
                    intersect: false,
                    mode: 'index'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor,
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}
</script>



