object w_publich_action: Tw_publich_action
  Left = 255
  Top = 197
  BorderIcons = [biSystemMenu]
  BorderStyle = bsSingle
  Caption = 'Select files to publish'
  ClientHeight = 434
  ClientWidth = 556
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
  object background: TPanel
    Left = 0
    Top = 393
    Width = 556
    Height = 41
    Align = alBottom
    BevelInner = bvRaised
    BevelOuter = bvLowered
    TabOrder = 0
    object _cancel: TButton
      Left = 472
      Top = 8
      Width = 75
      Height = 25
      Caption = 'Cancel'
      TabOrder = 0
      OnClick = _cancelClick
    end
    object _ok: TButton
      Left = 392
      Top = 8
      Width = 75
      Height = 25
      Caption = 'OK'
      Enabled = False
      TabOrder = 1
      Visible = False
      OnClick = _okClick
    end
    object _modified: TButton
      Left = 8
      Top = 8
      Width = 89
      Height = 25
      Caption = 'Check modified'
      TabOrder = 2
      OnClick = _modifiedClick
    end
    object _all: TButton
      Left = 104
      Top = 8
      Width = 89
      Height = 25
      Caption = 'Check all'
      TabOrder = 3
      OnClick = _allClick
    end
    object _none: TButton
      Left = 200
      Top = 8
      Width = 89
      Height = 25
      Caption = 'Uncheck all'
      TabOrder = 4
      OnClick = _noneClick
    end
    object _publish: TButton
      Left = 296
      Top = 8
      Width = 89
      Height = 25
      Caption = 'Publish'
      TabOrder = 5
      OnClick = _publishClick
    end
  end
  object display: TMemo
    Left = 0
    Top = 0
    Width = 556
    Height = 393
    Align = alClient
    ReadOnly = True
    ScrollBars = ssVertical
    TabOrder = 1
  end
  object files_list: TCheckListBox
    Left = 0
    Top = 0
    Width = 556
    Height = 393
    Align = alClient
    ItemHeight = 13
    TabOrder = 2
  end
end
