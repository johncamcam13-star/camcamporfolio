<?php
$srcPortrait = 'C:/Users/Administrator/.gemini/antigravity-ide/brain/8bfd3819-0562-4428-82f7-f805c6d6bd3f/.user_uploaded/media_1792762619244.png';
$dstPortrait = __DIR__ . '/assets/images/john_carlo_camcam.png';

$srcResume = 'C:/Users/Administrator/.gemini/antigravity-ide/brain/3596b4bd-bef4-401a-bd28-2ecbaad8af49/.user_uploaded/media_1793259344760.png';
$dstResumePng = __DIR__ . '/assets/images/student_resume.png';
$dstResumePgn = __DIR__ . '/assets/images/student_resume.pgn';

if (file_exists($srcPortrait)) {
    @copy($srcPortrait, $dstPortrait);
    echo "SUCCESS: Portrait copied.\n";
}

if (file_exists($srcResume)) {
    @copy($srcResume, $dstResumePng);
    @copy($srcResume, $dstResumePgn);
    echo "SUCCESS: Resume image copied to both student_resume.png and student_resume.pgn.\n";
}

// Synchronize student_resume.png and student_resume.pgn
if (file_exists($dstResumePng) && !file_exists($dstResumePgn)) {
    @copy($dstResumePng, $dstResumePgn);
    echo "SUCCESS: student_resume.pgn created from student_resume.png.\n";
} elseif (file_exists($dstResumePgn) && !file_exists($dstResumePng)) {
    @copy($dstResumePgn, $dstResumePng);
    echo "SUCCESS: student_resume.png created from student_resume.pgn.\n";
}
