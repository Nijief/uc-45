<?php
    require_once '../includes/config.php';

    header('Content-Type: application/json');

    $query = trim($_GET['q'] ?? '');

    if (strlen($query) < 2) {
        echo json_encode([]);
        exit;
    }

    $searchTerm = "%$query%";
    $results = [];

    try {
        $baseUrl = SITE_URL;
        
        $stmt = $pdo->prepare("SELECT id, title, content FROM news WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$searchTerm, $searchTerm]);
        $newsResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($newsResults as $news) {
            $description = strip_tags($news['content']);
            $description = mb_substr($description, 0, 100) . '...';
            
            $results[] = [
                'title' => htmlspecialchars($news['title']),
                'description' => htmlspecialchars($description),
                'url' => $baseUrl . '/pages/news_detail.php?id=' . $news['id'],
                'type' => 'news'
            ];
        }
        
        $stmt = $pdo->prepare("SELECT id, title, description FROM programs WHERE title LIKE ? OR description LIKE ? OR curriculum LIKE ? LIMIT 5");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        $programResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($programResults as $program) {
            $description = strip_tags($program['description']);
            $description = mb_substr($description, 0, 100) . '...';
            
            $results[] = [
                'title' => htmlspecialchars($program['title']),
                'description' => htmlspecialchars($description),
                'url' => $baseUrl . '/pages/program_detail.php?id=' . $program['id'],
                'type' => 'program'
            ];
        }
        
        echo json_encode($results);
        
    } catch (PDOException $e) {
        error_log("Search error: " . $e->getMessage());
        echo json_encode([]);
    }
?>