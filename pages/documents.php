<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Документы Учебного центра</h1>
        <p>Лицензии, приказы и нормативные документы</p>
    </div>
</div>

<div class="container">
    <div class="documents-list">
        
        <?php
        $documentsDir = '../assets/documents/';
        $documents = [];
        
        if (is_dir($documentsDir)) {
            $files = scandir($documentsDir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'rtf'];
                    if (in_array($ext, $allowedExt)) {
                        $documents[] = $file;
                    }
                }
            }
        }
        ?>
        
        <?php if (count($documents) > 0): ?>
            <ul class="documents-ul">
                <?php foreach ($documents as $doc): ?>
                    <?php
                    $ext = pathinfo($doc, PATHINFO_EXTENSION);
                    $fileName = pathinfo($doc, PATHINFO_FILENAME);
                    ?>
                    <li class="document-item">
                        <span class="doc-icon">
                            <?php if ($ext == 'pdf'): ?>
                            <?php elseif ($ext == 'doc' || $ext == 'docx'): ?>
                            <?php elseif ($ext == 'xls' || $ext == 'xlsx'): ?>
                            <?php else: ?>
                            <?php endif; ?>
                        </span>
                        <span class="doc-name"><?= htmlspecialchars($fileName) ?></span>
                        <a href="<?= SITE_URL ?>/assets/documents/<?= $doc ?>" class="doc-download" download>Скачать</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="documents-empty">
                <p>Документы временно отсутствуют</p>
                <p style="font-size: 0.85rem; color: #6c757d;">Поместите файлы в папку <strong>assets/documents/</strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>