<?php
$pageTitle = 'Tableau administratif';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container">
    <h1><i class="fas fa-shield-alt"></i> Tableau administratif</h1>
    
    <!-- Admin Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalUsers; ?></h3>
                <p>Utilisateurs totaux</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $newUsersThisMonth; ?></h3>
                <p>Nouveaux utilisateurs (ce mois)</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalPosts; ?></h3>
                <p>Posts totaux</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalOrders; ?></h3>
                <p>Commandes totales</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($totalRevenue, 2); ?> €</h3>
                <p>Revenus totaux</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #30cfd0, #330867);">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalUserPlants; ?></h3>
                <p>Plantes utilisateurs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #a8edea, #fed6e3);">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $totalCatalogPlants; ?></h3>
                <p>Plantes catalogue</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ff9a9e, #fecfef);">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $activeUsers; ?></h3>
                <p>Utilisateurs actifs (30j)</p>
            </div>
        </div>
    </div>
    
    <!-- Admin Charts Section -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Activité utilisateurs (30 derniers jours)</h3>
            <canvas id="userActivityChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Posts créés (30 derniers jours)</h3>
            <canvas id="postsActivityChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Commandes par statut</h3>
            <canvas id="ordersStatusChart"></canvas>
        </div>
        
        <div class="chart-card">
            <h3><i class="fas fa-heartbeat"></i> Santé du système</h3>
            <div class="system-health">
                <div class="health-item <?php echo $systemHealth['database'] ? 'healthy' : 'unhealthy'; ?>">
                    <i class="fas fa-database"></i>
                    <span>Base de données: <?php echo $systemHealth['database'] ? 'OK' : 'Erreur'; ?></span>
                </div>
                <div class="health-item <?php echo $systemHealth['disk_space'] ? 'healthy' : 'unhealthy'; ?>">
                    <i class="fas fa-hdd"></i>
                    <span>Espace disque: <?php echo $systemHealth['disk_space'] ? 'OK' : 'Critique'; ?></span>
                </div>
                <div class="health-item <?php echo $systemHealth['php_version'] ? 'healthy' : 'unhealthy'; ?>">
                    <i class="fas fa-code"></i>
                    <span>PHP Version: <?php echo PHP_VERSION; ?> <?php echo $systemHealth['php_version'] ? '✓' : '✗'; ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Dashboard Section (Same as regular dashboard) -->
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem;"><i class="fas fa-user"></i> Mon tableau de bord</h2>
    
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
    
    <!-- Admin Sections -->
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem;"><i class="fas fa-shield-alt"></i> Données administrateur</h2>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2><i class="fas fa-users"></i> Utilisateurs récents</h2>
            <?php if (empty($recentUsers)): ?>
                <p>Aucun utilisateur.</p>
            <?php else: ?>
                <ul class="activity-list">
                    <?php foreach ($recentUsers as $user): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['username']); ?>
                            </a>
                            <span class="date"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2><i class="fas fa-shopping-cart"></i> Commandes récentes</h2>
            <?php if (empty($recentOrders)): ?>
                <p>Aucune commande récente.</p>
            <?php else: ?>
                <ul class="activity-list">
                    <?php foreach ($recentOrders as $order): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($order['username']); ?></strong> - 
                            <?php echo number_format($order['total_amount'], 2); ?> €
                            <span class="date"><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></span>
                            <span class="badge badge-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span>
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
    gradient4: ['#51cf66', '#69db7c', '#8ce99a'],
    admin1: ['#667eea', '#764ba2'],
    admin2: ['#f093fb', '#f5576c'],
    admin3: ['#4facfe', '#00f2fe']
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

// User Activity Chart (Admin)
<?php
$userActivityLabels = [];
$userActivityData = [];
$days = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $days[$date] = 0;
    $userActivityLabels[] = date('d/m', strtotime("-$i days"));
}
foreach ($userActivityByDay as $day) {
    if (isset($days[$day['date']])) {
        $days[$day['date']] = (int)$day['count'];
    }
}
$userActivityData = array_values($days);
?>
const userActivityCtx = document.getElementById('userActivityChart');
if (userActivityCtx) {
    new Chart(userActivityCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($userActivityLabels); ?>,
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: <?php echo json_encode($userActivityData); ?>,
                borderColor: chartColors.admin1[0],
                backgroundColor: isDark ? 'rgba(102, 126, 234, 0.2)' : 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

// Posts Activity Chart (Admin)
<?php
$postsActivityLabels = [];
$postsActivityData = [];
$days = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $days[$date] = 0;
    $postsActivityLabels[] = date('d/m', strtotime("-$i days"));
}
foreach ($postsByDay as $day) {
    if (isset($days[$day['date']])) {
        $days[$day['date']] = (int)$day['count'];
    }
}
$postsActivityData = array_values($days);
?>
const postsActivityCtx = document.getElementById('postsActivityChart');
if (postsActivityCtx) {
    new Chart(postsActivityCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($postsActivityLabels); ?>,
            datasets: [{
                label: 'Posts créés',
                data: <?php echo json_encode($postsActivityData); ?>,
                backgroundColor: chartColors.admin2[0],
                borderColor: chartColors.admin2[1],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

// Orders Status Chart (Admin)
<?php
$ordersStatusLabels = [];
$ordersStatusData = [];
foreach ($ordersByStatus as $status) {
    $ordersStatusLabels[] = htmlspecialchars($status['status']);
    $ordersStatusData[] = (int)$status['count'];
}
?>
const ordersStatusCtx = document.getElementById('ordersStatusChart');
if (ordersStatusCtx && <?php echo json_encode(!empty($ordersStatusData)); ?>) {
    new Chart(ordersStatusCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($ordersStatusLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($ordersStatusData); ?>,
                backgroundColor: [
                    chartColors.primary,
                    chartColors.secondary,
                    chartColors.accent,
                    chartColors.success,
                    chartColors.info,
                    chartColors.warning,
                    chartColors.danger
                ],
                borderWidth: 0
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
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12
                }
            }
        }
    });
} else if (ordersStatusCtx) {
    ordersStatusCtx.parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--text-color); opacity: 0.7;">Aucune donnée disponible</p>';
}

// Room Chart (Doughnut) - User Dashboard
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
                borderWidth: 0
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
                        usePointStyle: true
                    }
                }
            }
        }
    });
} else if (roomCtx) {
    roomCtx.parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--text-color); opacity: 0.7;">Aucune donnée disponible</p>';
}

// Monthly Chart (Bar) - User Dashboard
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
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}

// Care Type Chart (Pie) - User Dashboard
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
                borderWidth: 0
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
                        usePointStyle: true
                    }
                }
            }
        }
    });
} else if (careTypeCtx) {
    careTypeCtx.parentElement.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--text-color); opacity: 0.7;">Aucune donnée disponible</p>';
}

// Daily Activity Chart (Line) - User Dashboard
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
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.95)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}
</script>

<style>
.system-health {
    padding: 1rem 0;
}

.health-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 8px;
    background: var(--card-bg);
    border: 2px solid transparent;
}

.health-item.healthy {
    border-color: #51cf66;
    background: rgba(81, 207, 102, 0.1);
}

.health-item.unhealthy {
    border-color: #ff6b6b;
    background: rgba(255, 107, 107, 0.1);
}

.health-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.health-item.healthy i {
    color: #51cf66;
}

.health-item.unhealthy i {
    color: #ff6b6b;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-pending {
    background: #ffd93d;
    color: #333;
}

.badge-processing {
    background: #4dabf7;
    color: #fff;
}

.badge-shipped {
    background: #9775fa;
    color: #fff;
}

.badge-delivered {
    background: #51cf66;
    color: #fff;
}

.badge-cancelled {
    background: #ff6b6b;
    color: #fff;
}
</style>

