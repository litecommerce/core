// FileUtil unit
#ifndef __FileUtilH
#define __FileUtilH
//-------------------------------------------------------------

#include <vcl.h>
#include <Regexpr.hpp>
#include <zlib.h>
//-------------------------------------------------------------

void get_files(AnsiString PathInicial, TStrings *file_list);
void rDeleteDir(AnsiString dirname);
bool createDirs(AnsiString dir);
AnsiString getDir(AnsiString dir);
AnsiString getFileName(AnsiString name);
int ReplaceInFile(AnsiString filename, AnsiString replace_from, AnsiString replace_to);
AnsiString updatePath(AnsiString path);
AnsiString updateUPath(AnsiString path);
bool UnZip(AnsiString infile, AnsiString outfile);
TStringList* SplitDirs(AnsiString dir);
TStringList* ConvertCR(AnsiString str);
#endif
