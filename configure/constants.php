<?php
define('tbltparSumRow',          'opt_HasSumField');
define('tbltparHasEmptyRow',     'opt_TableHasEmptyRow');
define('tbltparCanWrite',        'opt_CanWriteIntoTable');
define('tbltparTableDir',        'opt_TblSuund');
define('tbltparColumnCount',     'opt_Columns');
define('tbltparRowsBy',          'opt_RowsByCol');
define('tbltparsDeleteRow',      'opt_DeleteRow');
define('tbltparsColbyStat',      'opt_ColorByStatus');
define('tbltparsRowIDCol',       'opt_RowIdByName');

define('parsFieldDescr',         'opt_FieldDescr');
define('parsFieldType',          'opt_FieldType');
define('parsReadWrite',          'opt_ReadorWrite');
define('parsNumSep',             'opt_NumSep');


define('pdf_PrintLevel',         'opt_PrnYldNo');
define('pdf_Landscape',          'opt_Landscape');
define('pdf_ColWidth',           'opt_pdfColWidth');
define('pdf_NoPrint',            'opt_pdfNoPrint');


define('constAddNewRow',         'opt_LisaRida');
define('constSysAdmin',          'SysAdmin');
define('constAdmin',             'Admin');
define('constWrite',             'Write');

define('constTblHasNewRowYes',   '1');
define('constTblHasNewRowNo',    '0');

define('constRoW_R',             '0');
define('constRoW_W',             '1');


class PCA_tblt
{
/*

	tbltparTableDir => "vert",
	tbltparHasEmptyRow => 'SESSION_Write',
	tbltparCanWrite => 'SESSION_Write',
	tbltparSumRow => "0",
	tbltparsDeleteRow => 'SESSION_Write'


 
 */
const TableDir         = "opt_TblSuund";
	const DirVert      = "vert";
	const DirHori      = "hori";

const SumRow           = 'opt_HasSumField';

const HasEmptyRow      = 'opt_TableHasEmptyRow';
	const HasNewRowYes = 1;
	const HasNewRowNo  = 0;


const CanWrite         = 'opt_CanWriteIntoTable';
const ColumnCount      = 'opt_Columns';
const RowsBy           = 'opt_RowsByCol';
const DeleteRow        = 'opt_DeleteRow';
const ColbyStat        = 'opt_ColorByStatus';
const RowIDCol         = 'opt_RowIdByName';
const HidePrintPDF     = 'opt_HidePrintPDF';
	
}