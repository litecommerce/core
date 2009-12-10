{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10163: IdFTPCommon.pas 
{
{   Rev 1.0    2002.11.12 10:38:42 PM  czhower
}
unit IdFTPCommon;

interface

type
  TIdFTPTransferType = (ftASCII, ftBinary);
  TIdFTPDataStructure = (dsFile, dsRecord, dsPage);
  TIdFTPTransferMode = (dmBlock, dmCompressed, dmStream);

implementation

end.
