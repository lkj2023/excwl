<?php

namespace Maatwebsite\Excel\Tests;

use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HeadingRowImportTest extends TestCase
{
    /**
     * @test
     */
    public function can_import_only_heading_row()
    {
        $import = new HeadingRowImport();

        $headings = $import->toArray('import-users-with-headings.xlsx');

        $this->assertEquals([
            [
                ['name', 'email']
            ]
        ], $headings);
    }

    /**
     * @test
     */
    public function can_import_only_heading_row_with_custom_heading_row_formatter()
    {
        HeadingRowFormatter::extend('custom', function($value) {
             return 'custom-' . $value;
        });

        HeadingRowFormatter::default('custom');

        $import = new HeadingRowImport();

        $headings = $import->toArray('import-users-with-headings.xlsx');

        $this->assertEquals([
            [
                ['custom-name', 'custom-email']
            ]
        ], $headings);

        // Reset the formatter.
        HeadingRowFormatter::default();
    }

    /**
     * @test
     */
    public function can_import_only_heading_row_with_custom_row_number()
    {
        $import = new HeadingRowImport(2);

        $headings = $import->toArray('import-users-with-headings.xlsx');

        $this->assertEquals([
            [
                ['patrick-brouwers', 'patrick-at-maatwebsitenl']
            ]
        ], $headings);
    }

    /**
     * @test
     */
    public function can_import_only_heading_row_for_multiple_sheets()
    {
        $import = new HeadingRowImport();

        $headings = $import->toArray('import-multiple-sheets.xlsx');

        $this->assertEquals([
            [
                ['1a1', '1b1'] // slugged first row of sheet 1
            ],
            [
                ['2a1', '2b1'] // slugged first row of sheet 2
            ]
        ], $headings);
    }

    /**
     * @test
     */
    public function can_import_only_heading_row_for_multiple_sheets_with_custom_row_number()
    {
        $import = new HeadingRowImport(2);

        $headings = $import->toArray('import-multiple-sheets.xlsx');

        $this->assertEquals([
            [
                ['1a2', '1b2'] // slugged 2nd row of sheet 1
            ],
            [
                ['2a2', '2b2'] // slugged 2nd row of sheet 2
            ]
        ], $headings);
    }
}