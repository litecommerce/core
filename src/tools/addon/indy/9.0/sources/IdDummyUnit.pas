{ $HDR$}
{**********************************************************************}
{ Unit archived using Team Coherence                                   }
{ Team Coherence is Copyright 2002 by Quality Software Components      }
{                                                                      }
{ For further information / comments, visit our WEB site at            }
{ http://www.TeamCoherence.com                                         }
{**********************************************************************}
{}
{ $Log:  10143: IdDummyUnit.pas 
{
{   Rev 1.0    2002.11.12 10:36:54 PM  czhower
}
unit IdDummyUnit;
{

This unit is really not a part of Indy.  This unit's purpose is to trick the DCC32
compiler into generating .HPP and .OBJ files for run-time units that will not be
in the run-time package but will be on the palette.

Contributed by John Doe

}

interface
uses
    IdAntiFreeze;


implementation

{de-de-de-de, that's all folks.}

end.
