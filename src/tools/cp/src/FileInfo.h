#include <vcl.h>
#pragma hdrstop

#ifndef _FILE_INFO_H
#define _FILE_INFO_H

class fileinfo
{
	public:
		AnsiString name;
		AnsiString permissions;

		void getPermissions(AnsiString _permissions){
			permissions = "0";
			int i_perms[3] = {0, 0, 0};
			int value;
			for (int j = 0; j < 3; j++) {
				value = 4;
				for(int i = 0; i<3; i++) {
					if((_permissions.c_str())[3*j + i] != '-') {
						i_perms[j] += value;
					}
					value /=2;
				}
				permissions += IntToStr(i_perms[j]);
			}
		};
};

#endif
