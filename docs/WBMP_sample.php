<?
    require_once 'Images/WBMP.php';

    $a = new Image_WBMP();
    $a->createFromFile('WBMP_sample.wbmp');

    $a->drawFigletText('WBMP test', 'xbriteb.flf', '1:1', IMAGE_XBM_BLACK, IMAGE_XBM_WHITE, 23, 45);

    $a->drawRectangle(0, 0, 99, 99, IMAGE_XBM_BLACK);

    header("Content-type: image/vnd.wap.wbmp; level=0");
    $a->output();

?>