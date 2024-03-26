<?php

namespace Yurit\PdfHandler;

use ImagickException;

class PHP_Handle
{
    /**
     * @param string $finalFileName
     * @param array $pdfPaths
     * @return int|false
     * @throws ImagickException
     */
    public static function MergePDF(string $finalFileName, $pdfPaths): int|false
    {
        $mergePDF = new \Imagick();

        foreach ($pdfPaths as $pdf) {
            $tmp = new \Imagick();
            $tmp->readImage($pdf);

            $mergePDF->addImage($tmp);
            $tmp->clear();
            $tmp->destroy();
        }

        $mergePDF->setFormat('PDF');
        $mergePDF->setImageCompressionQuality(100);

        $tmpFile = tempnam(sys_get_temp_dir(), 'merge_');
        $mergePDF->writeImages($tmpFile, true);

        $result = file_put_contents(PATH_FINAL . $finalFileName . '.pdf', file_get_contents($tmpFile));

        unlink($tmpFile);
        $mergePDF->clear();
        $mergePDF->destroy();

        return $result;
    }
}