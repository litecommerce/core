// Borland C++ Builder
// Copyright (c) 1995, 1999 by Borland International
// All rights reserved

// (DO NOT EDIT: machine generated header) 'IdDateTimeStamp.pas' rev: 5.00

#ifndef IdDateTimeStampHPP
#define IdDateTimeStampHPP

#pragma delphiheader begin
#pragma option push -w-
#pragma option push -Vx
#include <SysUtils.hpp>	// Pascal unit
#include <SysConst.hpp>	// Pascal unit
#include <IdBaseComponent.hpp>	// Pascal unit
#include <Classes.hpp>	// Pascal unit
#include <SysInit.hpp>	// Pascal unit
#include <System.hpp>	// Pascal unit

//-- user supplied -----------------------------------------------------------

namespace Iddatetimestamp
{
//-- type declarations -------------------------------------------------------
typedef AnsiString IdDateTimeStamp__1[13];

typedef AnsiString IdDateTimeStamp__2[13];

typedef AnsiString IdDateTimeStamp__3[8];

typedef AnsiString IdDateTimeStamp__4[8];

#pragma option push -b-
enum TDays { TDaySun, TDayMon, TDayTue, TDayWed, TDayThu, TDayFri, TDaySat };
#pragma option pop

#pragma option push -b-
enum TMonths { TMthJan, TMthFeb, TMthMar, TMthApr, TMthMay, TMthJun, TMthJul, TMthAug, TMthSep, TMthOct, 
	TMthNov, TMthDec };
#pragma option pop

class DELPHICLASS TIdDateTimeStamp;
class PASCALIMPLEMENTATION TIdDateTimeStamp : public Idbasecomponent::TIdBaseComponent 
{
	typedef Idbasecomponent::TIdBaseComponent inherited;
	
protected:
	int FDay;
	bool FIsLeapYear;
	int FMillisecond;
	int FSecond;
	int FTimeZone;
	int FYear;
	void __fastcall CheckLeapYear(void);
	void __fastcall SetDateFromISO8601(AnsiString AString);
	void __fastcall SetTimeFromISO8601(AnsiString AString);
	
public:
	__fastcall virtual TIdDateTimeStamp(Classes::TComponent* AOwner);
	__fastcall virtual ~TIdDateTimeStamp(void);
	void __fastcall AddDays(unsigned ANumber);
	void __fastcall AddHours(unsigned ANumber);
	void __fastcall AddMilliseconds(unsigned ANumber);
	void __fastcall AddMinutes(unsigned ANumber);
	void __fastcall AddMonths(unsigned ANumber);
	void __fastcall AddSeconds(unsigned ANumber);
	void __fastcall AddTDateTime(System::TDateTime ADateTime);
	void __fastcall AddTIdDateTimeStamp(TIdDateTimeStamp* AIdDateTime);
	void __fastcall AddTTimeStamp(const Sysutils::TTimeStamp &ATimeStamp);
	void __fastcall AddWeeks(unsigned ANumber);
	void __fastcall AddYears(unsigned ANumber);
	AnsiString __fastcall GetAsISO8601Calendar();
	AnsiString __fastcall GetAsISO8601Ordinal();
	AnsiString __fastcall GetAsISO8601Week();
	AnsiString __fastcall GetAsRFC822();
	System::TDateTime __fastcall GetAsTDateTime(void);
	Sysutils::TTimeStamp __fastcall GetAsTTimeStamp();
	AnsiString __fastcall GetAsTimeOfDay();
	int __fastcall GetBeatOfDay(void);
	int __fastcall GetDaysInYear(void);
	int __fastcall GetDayOfMonth(void);
	int __fastcall GetDayOfWeek(void);
	AnsiString __fastcall GetDayOfWeekName();
	AnsiString __fastcall GetDayOfWeekShortName();
	int __fastcall GetHourOf12Day(void);
	int __fastcall GetHourOf24Day(void);
	bool __fastcall GetIsMorning(void);
	int __fastcall GetMinuteOfDay(void);
	int __fastcall GetMinuteOfHour(void);
	int __fastcall GetMonthOfYear(void);
	AnsiString __fastcall GetMonthName();
	AnsiString __fastcall GetMonthShortName();
	int __fastcall GetSecondsInYear(void);
	int __fastcall GetSecondOfMinute(void);
	AnsiString __fastcall GetTimeZoneAsString();
	int __fastcall GetTimeZoneHour(void);
	int __fastcall GetTimeZoneMinutes(void);
	int __fastcall GetWeekOfYear(void);
	void __fastcall SetFromDOSDateTime(Word ADate, Word ATime);
	void __fastcall SetFromISO8601(AnsiString AString);
	void __fastcall SetFromRFC822(AnsiString AString);
	void __fastcall SetFromTDateTime(System::TDateTime ADateTime);
	void __fastcall SetFromTTimeStamp(const Sysutils::TTimeStamp &ATimeStamp);
	void __fastcall SetDay(int ANumber);
	void __fastcall SetMillisecond(int ANumber);
	void __fastcall SetSecond(int ANumber);
	void __fastcall SetTimeZone(const int Value);
	void __fastcall SetYear(int ANumber);
	void __fastcall SubtractDays(unsigned ANumber);
	void __fastcall SubtractHours(unsigned ANumber);
	void __fastcall SubtractMilliseconds(unsigned ANumber);
	void __fastcall SubtractMinutes(unsigned ANumber);
	void __fastcall SubtractMonths(unsigned ANumber);
	void __fastcall SubtractSeconds(unsigned ANumber);
	void __fastcall SubtractTDateTime(System::TDateTime ADateTime);
	void __fastcall SubtractTIdDateTimeStamp(TIdDateTimeStamp* AIdDateTime);
	void __fastcall SubtractTTimeStamp(const Sysutils::TTimeStamp &ATimeStamp);
	void __fastcall SubtractWeeks(unsigned ANumber);
	void __fastcall SubtractYears(unsigned ANumber);
	void __fastcall Zero(void);
	void __fastcall ZeroDate(void);
	void __fastcall ZeroTime(void);
	__property AnsiString AsISO8601Calendar = {read=GetAsISO8601Calendar};
	__property AnsiString AsISO8601Ordinal = {read=GetAsISO8601Ordinal};
	__property AnsiString AsISO8601Week = {read=GetAsISO8601Week};
	__property AnsiString AsRFC822 = {read=GetAsRFC822};
	__property System::TDateTime AsTDateTime = {read=GetAsTDateTime};
	__property Sysutils::TTimeStamp AsTTimeStamp = {read=GetAsTTimeStamp};
	__property AnsiString AsTimeOfDay = {read=GetAsTimeOfDay};
	__property int BeatOfDay = {read=GetBeatOfDay, nodefault};
	__property int Day = {read=FDay, write=SetDay, nodefault};
	__property int DaysInYear = {read=GetDaysInYear, nodefault};
	__property int DayOfMonth = {read=GetDayOfMonth, nodefault};
	__property int DayOfWeek = {read=GetDayOfWeek, nodefault};
	__property AnsiString DayOfWeekName = {read=GetDayOfWeekName};
	__property AnsiString DayOfWeekShortName = {read=GetDayOfWeekShortName};
	__property int HourOf12Day = {read=GetHourOf12Day, nodefault};
	__property int HourOf24Day = {read=GetHourOf24Day, nodefault};
	__property bool IsLeapYear = {read=FIsLeapYear, nodefault};
	__property bool IsMorning = {read=GetIsMorning, nodefault};
	__property int Millisecond = {read=FMillisecond, write=SetMillisecond, nodefault};
	__property int MinuteOfDay = {read=GetMinuteOfDay, nodefault};
	__property int MinuteOfHour = {read=GetMinuteOfHour, nodefault};
	__property int MonthOfYear = {read=GetMonthOfYear, nodefault};
	__property AnsiString MonthName = {read=GetMonthName};
	__property AnsiString MonthShortName = {read=GetMonthShortName};
	__property int Second = {read=FSecond, write=SetSecond, nodefault};
	__property int SecondsInYear = {read=GetSecondsInYear, nodefault};
	__property int SecondOfMinute = {read=GetSecondOfMinute, nodefault};
	__property int TimeZone = {read=FTimeZone, write=SetTimeZone, nodefault};
	__property int TimeZoneHour = {read=GetTimeZoneHour, nodefault};
	__property int TimeZoneMinutes = {read=GetTimeZoneMinutes, nodefault};
	__property AnsiString TimeZoneAsString = {read=GetTimeZoneAsString};
	__property int Year = {read=FYear, write=SetYear, nodefault};
	__property int WeekOfYear = {read=GetWeekOfYear, nodefault};
};


//-- var, const, procedure ---------------------------------------------------
static const Word IdMilliSecondsInSecond = 0x3e8;
static const Shortint IdSecondsInMinute = 0x3c;
static const Shortint IdMinutesInHour = 0x3c;
static const Shortint IdHoursInDay = 0x18;
static const Shortint IdDaysInWeek = 0x7;
static const Word IdDaysInYear = 0x16d;
static const Word IdDaysInLeapYear = 0x16e;
static const Shortint IdYearsInShortLeapYearCycle = 0x4;
static const Word IdDaysInShortLeapYearCycle = 0x5b5;
static const Word IdDaysInShortNonLeapYearCycle = 0x5b4;
static const Word IdDaysInFourYears = 0x5b5;
static const Shortint IdYearsInCentury = 0x64;
static const Word IdDaysInCentury = 0x8eac;
static const Word IdDaysInLeapCentury = 0x8ead;
static const Word IdYearsInLeapYearCycle = 0x190;
static const int IdDaysInLeapYearCycle = 0x23ab1;
static const Shortint IdMonthsInYear = 0xc;
static const Word IdBeatsInDay = 0x3e8;
static const Shortint IdHoursInHalfDay = 0xc;
static const Word IdSecondsInHour = 0xe10;
static const int IdSecondsInDay = 0x15180;
static const Word IdSecondsInHalfDay = 0xa8c0;
static const int IdSecondsInWeek = 0x93a80;
static const int IdSecondsInYear = 0x1e13380;
static const int IdSecondsInLeapYear = 0x1e28500;
static const Word IdMillisecondsInMinute = 0xea60;
static const int IdMillisecondsInHour = 0x36ee80;
static const int IdMillisecondsInDay = 0x5265c00;
static const int IdMillisecondsInWeek = 0x240c8400;
extern PACKAGE Byte IdDaysInMonth[12];
extern PACKAGE AnsiString IdMonthNames[13];
extern PACKAGE AnsiString IdMonthShortNames[13];
extern PACKAGE AnsiString IdDayNames[8];
extern PACKAGE AnsiString IdDayShortNames[8];
static const Shortint TZ_NZDT = 0xd;
static const Shortint TZ_IDLE = 0xc;
static const Shortint TZ_NZST = 0xc;
static const Shortint TZ_NZT = 0xc;
static const Shortint TZ_EADT = 0xb;
static const Shortint TZ_GST = 0xa;
static const Shortint TZ_JST = 0x9;
static const Shortint TZ_CCT = 0x8;
static const Shortint TZ_WADT = 0x8;
static const Shortint TZ_WAST = 0x7;
static const Shortint TZ_ZP6 = 0x6;
static const Shortint TZ_ZP5 = 0x5;
static const Shortint TZ_ZP4 = 0x4;
static const Shortint TZ_BT = 0x3;
static const Shortint TZ_EET = 0x2;
static const Shortint TZ_MEST = 0x2;
static const Shortint TZ_MESZ = 0x2;
static const Shortint TZ_SST = 0x2;
static const Shortint TZ_FST = 0x2;
static const Shortint TZ_CET = 0x1;
static const Shortint TZ_FWT = 0x1;
static const Shortint TZ_MET = 0x1;
static const Shortint TZ_MEWT = 0x1;
static const Shortint TZ_SWT = 0x1;
static const Shortint TZ_GMT = 0x0;
static const Shortint TZ_UT = 0x0;
static const Shortint TZ_UTC = 0x0;
static const Shortint TZ_WET = 0x0;
static const Shortint TZ_WAT = 0xffffffff;
static const Shortint TZ_BST = 0xffffffff;
static const Shortint TZ_AT = 0xfffffffe;
static const Shortint TZ_ADT = 0xfffffffd;
static const Shortint TZ_AST = 0xfffffffc;
static const Shortint TZ_EDT = 0xfffffffc;
static const Shortint TZ_EST = 0xfffffffb;
static const Shortint TZ_CDT = 0xfffffffb;
static const Shortint TZ_CST = 0xfffffffa;
static const Shortint TZ_MDT = 0xfffffffa;
static const Shortint TZ_MST = 0xfffffff9;
static const Shortint TZ_PDT = 0xfffffff9;
static const Shortint TZ_PST = 0xfffffff8;
static const Shortint TZ_YDT = 0xfffffff8;
static const Shortint TZ_YST = 0xfffffff7;
static const Shortint TZ_HDT = 0xfffffff7;
static const Shortint TZ_AHST = 0xfffffff6;
static const Shortint TZ_CAT = 0xfffffff6;
static const Shortint TZ_HST = 0xfffffff6;
static const Shortint TZ_EAST = 0xfffffff6;
static const Shortint TZ_NT = 0xfffffff5;
static const Shortint TZ_IDLW = 0xfffffff4;
static const Shortint TZM_A = 0xffffffff;
static const Shortint TZM_Alpha = 0xffffffff;
static const Shortint TZM_B = 0xfffffffe;
static const Shortint TZM_Bravo = 0xfffffffe;
static const Shortint TZM_C = 0xfffffffd;
static const Shortint TZM_Charlie = 0xfffffffd;
static const Shortint TZM_D = 0xfffffffc;
static const Shortint TZM_Delta = 0xfffffffc;
static const Shortint TZM_E = 0xfffffffb;
static const Shortint TZM_Echo = 0xfffffffb;
static const Shortint TZM_F = 0xfffffffa;
static const Shortint TZM_Foxtrot = 0xfffffffa;
static const Shortint TZM_G = 0xfffffff9;
static const Shortint TZM_Golf = 0xfffffff9;
static const Shortint TZM_H = 0xfffffff8;
static const Shortint TZM_Hotel = 0xfffffff8;
static const Shortint TZM_J = 0xfffffff7;
static const Shortint TZM_Juliet = 0xfffffff7;
static const Shortint TZM_K = 0xfffffff6;
static const Shortint TZM_Kilo = 0xfffffff6;
static const Shortint TZM_L = 0xfffffff5;
static const Shortint TZM_Lima = 0xfffffff5;
static const Shortint TZM_M = 0xfffffff4;
static const Shortint TZM_Mike = 0xfffffff4;
static const Shortint TZM_N = 0x1;
static const Shortint TZM_November = 0x1;
static const Shortint TZM_O = 0x2;
static const Shortint TZM_Oscar = 0x2;
static const Shortint TZM_P = 0x3;
static const Shortint TZM_Papa = 0x3;
static const Shortint TZM_Q = 0x4;
static const Shortint TZM_Quebec = 0x4;
static const Shortint TZM_R = 0x5;
static const Shortint TZM_Romeo = 0x5;
static const Shortint TZM_S = 0x6;
static const Shortint TZM_Sierra = 0x6;
static const Shortint TZM_T = 0x7;
static const Shortint TZM_Tango = 0x7;
static const Shortint TZM_U = 0x8;
static const Shortint TZM_Uniform = 0x8;
static const Shortint TZM_V = 0x9;
static const Shortint TZM_Victor = 0x9;
static const Shortint TZM_W = 0xa;
static const Shortint TZM_Whiskey = 0xa;
static const Shortint TZM_X = 0xfffffff5;
static const Shortint TZM_XRay = 0xfffffff5;
static const Shortint TZM_Y = 0xc;
static const Shortint TZM_Yankee = 0xc;
static const Shortint TZM_Z = 0x0;
static const Shortint TZM_Zulu = 0x0;

}	/* namespace Iddatetimestamp */
#if !defined(NO_IMPLICIT_NAMESPACE_USE)
using namespace Iddatetimestamp;
#endif
#pragma option pop	// -w-
#pragma option pop	// -Vx

#pragma delphiheader end.
//-- end unit ----------------------------------------------------------------
#endif	// IdDateTimeStamp
