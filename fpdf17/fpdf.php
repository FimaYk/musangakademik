<?php
/*******************************************************************************
* FPDF                                                                         *
*                                                                              *
* Version: 1.7                                                                 *
* Date:    2011-06-18                                                          *
* Author:  Olivier PLATHEY                                                     *
*******************************************************************************/

define('FPDF_VERSION', '1.7');

class FPDF
{
    var $page;               // current page number
    var $n;                  // current object number
    var $offsets;            // array of object offsets
    var $buffer;             // buffer holding in-memory PDF
    var $pages;              // array containing pages
    var $state;              // current document state
    var $compress;           // compression flag
    var $k;                  // scale factor (number of points in user unit)
    var $DefOrientation;     // default orientation
    var $CurOrientation;     // current orientation
    var $StdPageSizes;       // standard page sizes
    var $DefPageSize;        // default page size
    var $CurPageSize;        // current page size
    var $PageSizes;          // used for pages with non-default sizes or orientations
    var $wPt, $hPt;          // dimensions of current page in points
    var $w, $h;              // dimensions of current page in user unit
    var $lMargin;            // left margin
    var $tMargin;            // top margin
    var $rMargin;            // right margin
    var $bMargin;            // page break margin
    var $cMargin;            // cell margin
    var $x, $y;              // current position in user unit
    var $lasth;              // height of last printed cell
    var $LineWidth;          // line width in user unit
    var $fontpath;           // path containing fonts
    var $CoreFonts;          // array of core font names
    var $fonts;              // array of used fonts
    var $FontFiles;          // array of font files
    var $diffs;              // array of encoding differences
    var $FontFamily;         // current font family
    var $FontStyle;          // current font style
    var $underline;          // underlining flag
    var $CurrentFont;        // current font info
    var $FontSizePt;         // current font size in points
    var $FontSize;           // current font size in user unit
    var $DrawColor;          // commands for drawing color
    var $FillColor;          // commands for filling color
    var $TextColor;          // commands for text color
    var $ColorFlag;          // indicates whether fill and text colors are different
    var $ws;                 // word spacing
    var $images;             // array of used images
    var $PageLinks;          // array of links in pages
    var $links;              // array of internal links
    var $AutoPageBreak;      // automatic page breaking
    var $PageBreakTrigger;   // threshold used to trigger page breaks
    var $InHeader;           // flag set when processing header
    var $InFooter;           // flag set when processing footer
    var $ZoomMode;           // zoom display mode
    var $LayoutMode;         // layout display mode
    var $title;              // title
    var $subject;            // subject
    var $author;             // author
    var $keywords;           // keywords
    var $creator;            // creator
    var $AliasNbPages;       // alias for total number of pages
    var $PDFVersion;         // PDF version number

    /*******************************************************************************
    *                                                                              *
    *                               Public methods                                 *
    *                                                                              *
    *******************************************************************************/
    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        // Some checks
        $this->_dochecks();
        // Initialization of properties
        $this->page = 0;
        $this->n = 2;
        $this->buffer = '';
        $this->pages = array();
        $this->PageSizes = array();
        $this->state = 0;
        $this->fonts = array();
        $this->FontFiles = array();
        $this->diffs = array();
        $this->images = array();
        $this->links = array();
        $this->InHeader = false;
        $this->InFooter = false;
        $this->lasth = 0;
        $this->FontFamily = '';
        $this->FontStyle = '';
        $this->FontSizePt = 12;
        $this->underline = false;
        $this->DrawColor = '0 G';
        $this->FillColor = '0 g';
        $this->TextColor = '0 g';
        $this->ColorFlag = false;
        $this->ws = 0;

        // Font path
        if (defined('FPDF_FONTPATH')) {
            $this->fontpath = FPDF_FONTPATH;
            if (substr($this->fontpath, -1) != '/' && substr($this->fontpath, -1) != '\\') {
                $this->fontpath .= '/';
            }
        } elseif (is_dir(dirname(__FILE__) . '/font')) {
            $this->fontpath = dirname(__FILE__) . '/font/';
        } else {
            $this->fontpath = '';
        }

        // Core fonts
        $this->CoreFonts = array('courier', 'helvetica', 'times', 'symbol', 'zapfdingbats');

        // Scale factor
        if ($unit == 'pt') {
            $this->k = 1;
        } elseif ($unit == 'mm') {
            $this->k = 72 / 25.4;
        } elseif ($unit == 'cm') {
            $this->k = 72 / 2.54;
        } elseif ($unit == 'in') {
            $this->k = 72;
        } else {
            $this->Error('Incorrect unit: ' . $unit);
        }

        // Page sizes
        $this->StdPageSizes = array(
            'a3' => array(841.89, 1190.55),
            'a4' => array(595.28, 841.89),
            'a5' => array(420.94, 595.28),
            'letter' => array(612, 792),
            'legal' => array(612, 1008)
        );

        $size = $this->_getpagesize($size);
        $this->DefPageSize = $size;
        $this->CurPageSize = $size;

        // Page orientation
        $orientation = strtolower($orientation);
        if ($orientation == 'p' || $orientation == 'portrait') {
            $this->DefOrientation = 'P';
            $this->w = $size[0];
            $this->h = $size[1];
        } elseif ($orientation == 'l' || $orientation == 'landscape') {
            $this->DefOrientation = 'L';
            $this->w = $size[1];
            $this->h = $size[0];
        } else {
            $this->Error('Incorrect orientation: ' . $orientation);
        }

        $this->CurOrientation = $this->DefOrientation;
        $this->wPt = $this->w * $this->k;
        $this->hPt = $this->h * $this->k;

        // Page margins (1 cm)
        $margin = 28.35 / $this->k;
        $this->SetMargins($margin, $margin);

        // Interior cell margin (1 mm)
        $this->cMargin = $margin / 10;

        // Line width (0.2 mm)
        $this->LineWidth = .567 / $this->k;

        // Automatic page break
        $this->SetAutoPageBreak(true, 2 * $margin);

        // Default display mode
        $this->SetDisplayMode('default');

        // Enable compression
        $this->SetCompression(true);

        // Set default PDF version number
        $this->PDFVersion = '1.3';
    }

    function SetMargins($left, $top, $right = null)
    {
        // Set left, top and right margins
        $this->lMargin = $left;
        $this->tMargin = $top;
        if ($right === null) {
            $right = $left;
        }
        $this->rMargin = $right;
    }

    function SetLeftMargin($margin)
    {
        // Set left margin
        $this->lMargin = $margin;
        if ($this->page > 0 && $this->x < $margin) {
            $this->x = $margin;
        }
    }

    function SetTopMargin($margin)
    {
        // Set top margin
        $this->tMargin = $margin;
    }

    function SetRightMargin($margin)
    {
        // Set right margin
        $this->rMargin = $margin;
    }

    function SetAutoPageBreak($auto, $margin = 0)
    {
        // Set auto page break mode and triggering margin
        $this->AutoPageBreak = $auto;
        $this->bMargin = $margin;
        $this->PageBreakTrigger = $this->h - $margin;
    }

    function SetDisplayMode($zoom, $layout = 'default')
    {
        // Set display mode in viewer
        if ($zoom == 'fullpage' || $zoom == 'fullwidth' || $zoom == 'real' || $zoom == 'default' || !is_string($zoom)) {
            $this->ZoomMode = $zoom;
        } else {
            $this->Error('Incorrect zoom display mode: ' . $zoom);
        }
        if ($layout == 'single' || $layout == 'continuous' || $layout == 'two' || $layout == 'default') {
            $this->LayoutMode = $layout;
        } else {
            $this->Error('Incorrect layout display mode: ' . $layout);
        }
    }

    function SetCompression($compress)
    {
        // Set page compression
        if (function_exists('gzcompress')) {
            $this->compress = $compress;
        } else {
            $this->compress = false;
        }
    }

    function SetTitle($title, $isUTF8 = false)
    {
        // Title of document
        if ($isUTF8) {
            $title = $this->_UTF8toUTF16($title);
        }
        $this->title = $title;
    }

    function SetSubject($subject, $isUTF8 = false)
    {
        // Subject of document
        if ($isUTF8) {
            $subject = $this->_UTF8toUTF16($subject);
        }
        $this->subject = $subject;
    }

    function SetAuthor($author, $isUTF8 = false)
    {
        // Author of document
        if ($isUTF8) {
            $author = $this->_UTF8toUTF16($author);
        }
        $this->author = $author;
    }

    function SetKeywords($keywords, $isUTF8 = false)
    {
        // Keywords of document
        if ($isUTF8) {
            $keywords = $this->_UTF8toUTF16($keywords);
        }
        $this->keywords = $keywords;
    }

    function SetCreator($creator, $isUTF8 = false)
    {
        // Creator of document
        if ($isUTF8) {
            $creator = $this->_UTF8toUTF16($creator);
        }
        $this->creator = $creator;
    }

    function AliasNbPages($alias = '{nb}')
    {
        // Define an alias for total number of pages
        $this->AliasNbPages = $alias;
    }

    function Error($msg)
    {
        // Fatal error
        die('<b>FPDF error:</b> ' . $msg);
    }

    function Open()
    {
        // Begin document
        $this->state = 1;
    }

    function Close()
    {
        // Terminate document
        if ($this->state == 3) {
            return;
        }
        if ($this->page == 0) {
            $this->AddPage();
        }
        // Page footer
        $this->InFooter = true;
        $this->Footer();
        $this->InFooter = false;
        // Close page
        $this->_endpage();
        // Close document
        $this->_enddoc();
    }

    function AddPage($orientation = '', $size = '')
    {
        // Start a new page
        if ($this->state == 0) {
            $this->Open();
        }
        $family = $this->FontFamily;
        $style = $this->FontStyle . ($this->underline ? 'U' : '');
        $fontsize = $this->FontSizePt;
        $lw = $this->LineWidth;
        $dc = $this->DrawColor;
        $fc = $this->FillColor;
        $tc = $this->TextColor;
        $cf = $this->ColorFlag;

        if ($this->page > 0) {
            // Page footer
            $this->InFooter = true;
            $this->Footer();
            $this->InFooter = false;
            // Close page
            $this->_endpage();
        }

        // Start new page
        $this->_beginpage($orientation, $size);
        // Set line cap style to square
        $this->_out('2 J');
        // Set line width
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w', $lw * $this->k));
        // Set font
        if ($family) {
            $this->SetFont($family, $style, $fontsize);
        }
        // Set colors
        $this->DrawColor = $dc;
        if ($dc != '0 G') {
            $this->_out($dc);
        }
        $this->FillColor = $fc;
        if ($fc != '0 g') {
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
        // Page header
        $this->InHeader = true;
        $this->Header();
        $this->InHeader = false;
        // Restore line width
        if ($this->LineWidth != $lw) {
            $this->LineWidth = $lw;
            $this->_out(sprintf('%.2F w', $lw * $this->k));
        }
        // Restore font
        if ($family) {
            $this->SetFont($family, $style, $fontsize);
        }
        // Restore colors
        if ($this->DrawColor != $dc) {
            $this->DrawColor = $dc;
            $this->_out($dc);
        }
        if ($this->FillColor != $fc) {
            $this->FillColor = $fc;
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    function Header()
    {
        // To be implemented in your own inherited class
    }

    function Footer()
    {
        // To be implemented in your own inherited class
    }

    function PageNo()
    {
        // Get current page number
        return $this->page;
    }

    function SetDrawColor($r, $g = null, $b = null)
    {
        // Set color for all stroking operations
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->DrawColor = sprintf('%.3F G', $r / 255);
        } else {
            $this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
        }
        if ($this->page > 0) {
            $this->_out($this->DrawColor);
        }
    }

    function SetFillColor($r, $g = null, $b = null)
    {
        // Set color for all filling operations
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->FillColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
        if ($this->page > 0) {
            $this->_out($this->FillColor);
        }
    }

    function SetTextColor($r, $g = null, $b = null)
    {
        // Set color for text
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->TextColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
    }

    function GetStringWidth($s)
    {
        // Get width of a string in the current font
        $s = (string)$s;
        $cw = &$this->CurrentFont['cw'];
        $w = 0;
        $l = strlen($s);
        for ($i = 0; $i < $l; $i++) {
            $w += $cw[$s[$i]];
        }
        return $w * $this->FontSize / 1000;
    }

    function SetLineWidth($width)
    {
        // Set line width
        $this->LineWidth = $width;
        if ($this->page > 0) {
            $this->_out(sprintf('%.2F w', $width * $this->k));
        }
    }

    function Line($x1, $y1, $x2, $y2)
    {
        // Draw a line
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F l S', $x1 * $this->k, ($this