object directory_choice: Tdirectory_choice
  Left = 370
  Top = 296
  Width = 343
  Height = 241
  Caption = 'Choose directory'
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  PixelsPerInch = 96
  TextHeight = 13
  object dir_ok: TButton
    Left = 240
    Top = 8
    Width = 89
    Height = 25
    Caption = 'Ok'
    TabOrder = 0
    OnClick = dir_okClick
  end
  object dir_cancel: TButton
    Left = 240
    Top = 40
    Width = 89
    Height = 25
    Caption = 'Cancel'
    TabOrder = 1
    OnClick = dir_cancelClick
  end
  object create_dir: TButton
    Left = 240
    Top = 72
    Width = 89
    Height = 25
    Caption = 'New directory'
    TabOrder = 2
    OnClick = create_dirClick
  end
  object dir_list: TDirectoryListBox
    Left = 4
    Top = 8
    Width = 230
    Height = 201
    ItemHeight = 16
    TabOrder = 3
  end
  object createdir_popup: TPopupMenu
    Left = 240
    Top = 136
    object C1: TMenuItem
      Caption = 'Create new'
      OnClick = C1Click
    end
  end
end
