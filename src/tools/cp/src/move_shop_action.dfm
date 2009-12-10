object move_shop_act: Tmove_shop_act
  Left = 497
  Top = 235
  Width = 370
  Height = 370
  Caption = 'Move shop'
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  OnCreate = FormCreate
  PixelsPerInch = 96
  TextHeight = 13
  object Label1: TLabel
    Left = 8
    Top = 288
    Width = 69
    Height = 13
    Caption = 'Downloaded : '
    Constraints.MaxWidth = 350
    Visible = False
  end
  object bytes_log: TLabel
    Left = 80
    Top = 288
    Width = 3
    Height = 13
    Visible = False
  end
  object move_log: TMemo
    Left = 5
    Top = 8
    Width = 353
    Height = 273
    TabOrder = 0
  end
  object _ok: TButton
    Left = 281
    Top = 312
    Width = 75
    Height = 25
    Caption = 'Ok'
    TabOrder = 1
  end
  object _cancel: TButton
    Left = 198
    Top = 312
    Width = 75
    Height = 25
    Caption = 'Cancel'
    TabOrder = 2
    OnClick = _cancelClick
  end
end
