#include "FileUtil.h"

void get_files(AnsiString PathInicial, TStrings *file_list)
{
	TSearchRec SearchRec;
	int Result;
	AnsiString tempstr;

	tempstr.cat_printf("%s\\*.*",PathInicial);
	Result = FindFirst(tempstr, faAnyFile, SearchRec);
	while (Result == 0) {
		tempstr = "";
		tempstr.cat_printf("%s\\%s",PathInicial,SearchRec.Name);
		if (SearchRec.Name != "." && SearchRec.Name != ".."&& SearchRec.Name != "anchors.local"&& SearchRec.Name != "timestamp.local"&& SearchRec.Name != ".anchors.ini"){
			if (SearchRec.Attr & faDirectory)
				get_files(tempstr, file_list);
			else
				file_list->Add(tempstr);
		}
		Result = FindNext(SearchRec);
	}
	FindClose(SearchRec);
}

void rDeleteDir(AnsiString dirname) {
	TSearchRec SearchRec;
	int Result;
	AnsiString tempstr;

	tempstr.cat_printf("%s\\*.*",dirname);
	Result = FindFirst(tempstr, faAnyFile, SearchRec);
	while (Result == 0) {
		tempstr = "";
		tempstr.cat_printf("%s\\%s",dirname, SearchRec.Name);
		if (SearchRec.Name != "." && SearchRec.Name != ".."){
			if (SearchRec.Attr & faDirectory) {
				rDeleteDir(tempstr);
				RemoveDirectory(tempstr.c_str());
			} else {
				DeleteFile(tempstr);
			}  
		}
		Result = FindNext(SearchRec);
	}
	FindClose(SearchRec);
	RemoveDirectory(dirname.c_str());
}

bool createDirs(AnsiString dir)
{
	AnsiString c_dir = GetCurrentDir();
	TRegExpr *regex = new TRegExpr();
	TStringList *out = new TStringList();
	regex->Expression = "[\\\\\/]";
	regex->Split(dir, out);
	for (int i=0; i<out->Count; i++) {
		if (out->Strings[i].Pos(":") != 0) {
			SetCurrentDir(out->Strings[i] + "\\");
		} else {
			CreateDirectory(out->Strings[i].c_str(), NULL);
			if (!SetCurrentDir(out->Strings[i] + "\\")) {
				return false;
			}
		}
	}
	delete out;
	delete regex;
	SetCurrentDir(c_dir);
	return true;
}

AnsiString getDir(AnsiString dir)
{
	int pos;
	pos = dir.LastDelimiter("\\/");
	return dir.SubString(1, pos);
}

AnsiString getFileName(AnsiString name)
{
	TRegExpr *regex = new TRegExpr();
	TStringList *out = new TStringList();
	regex->Expression = "[\\\\\/]";
	regex->Split(name, out);
	AnsiString result = out->Strings[out->Count - 1];
	delete out;
	delete regex;
	return result;

}

int ReplaceInFile(AnsiString filename, AnsiString replace_from,
		AnsiString replace_to)
{
	TStringList* file = new TStringList();
	try {
		file->LoadFromFile(filename);
	} catch (...) {
		delete file;
		return -1; //could not read from file
	}
	TRegExpr *regex = new TRegExpr();
	regex->Expression = replace_from;
	for (int i = 0; i < file->Count; i++) {
		file->Strings[i] = regex->Replace(file->Strings[i], replace_to, false);
	}
	delete regex;

	file->SaveToFile(filename);
	delete file;
	return 0;
}

AnsiString updatePath(AnsiString path)
{
	TRegExpr *regex = new TRegExpr();
	regex->Expression = "var\/html\/";
	AnsiString result = regex->Replace(path, "", true);
	regex->Expression = "\/";
	result = regex->Replace(result, "\\", true);
	delete regex;

	return result;
}

AnsiString updateUPath(AnsiString path)
{
	TRegExpr *regex = new TRegExpr();
	regex->Expression = "\\\\";
	AnsiString result = regex->Replace(path, "\/", true);
	delete regex;

	return result;
}

bool UnZip(AnsiString infile, AnsiString outfile)
{
	int buffer_size = 512;

	TFileStream* fs = new TFileStream(outfile, fmCreate);

	System::Byte *buffer = new System::Byte[buffer_size];
	gzFile gzf = gzopen(infile.c_str(), "rb");
	if (!gzf) {
		delete fs;
		delete buffer;
		DeleteFile(outfile);
		return false;
	}
	int result;
	while((result = gzread(gzf, buffer, buffer_size)) != 0) {
		fs->Write((void*)buffer, result);
	}
	gzclose(gzf);

	delete fs;
	delete buffer;
	return true;
}

TStringList* GetVersion(AnsiString filename)
{
	TStringList *version = new TStringList();
	try {
		version->LoadFromFile(filename);
	} catch (...) {
		delete version;
		return NULL;
	}
	return version;
}

TStringList* SplitDirs(AnsiString dir)
{
	TRegExpr *regex = new TRegExpr();
   TStringList* result = new TStringList();
   regex->Expression = "[\\\/]";
   regex->Split(dir, result);
   delete regex;

   int count = result->Count;
   if (count < 1) {
   	return result;
   }
   for (int i = count - 1; i > 0; i--) {
      AnsiString tmp = result->Strings[i];
   	for (int j = i-1; j > -1; j--) {
      	tmp = result->Strings[j] + "/" + tmp;
      }
      result->Strings[i] = tmp;
   }
   return result;
}

TStringList* ConvertCR(AnsiString str)
{
   TStringList* result = new TStringList();

	TRegExpr *regex = new TRegExpr();
   regex->Expression = "[\n]";
   regex->Split(str, result);
   delete regex;

   return result;
}
