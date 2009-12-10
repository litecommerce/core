object ProtoThreadForm: TProtoThreadForm
  Left = 254
  Top = 224
  BorderIcons = [biSystemMenu, biMinimize]
  BorderStyle = bsSingle
  Caption = 'ProtoThreadForm'
  ClientHeight = 333
  ClientWidth = 532
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  KeyPreview = True
  OldCreateOrder = False
  OnClose = FormClose
  OnCloseQuery = FormCloseQuery
  OnKeyPress = FormKeyPress
  PixelsPerInch = 96
  TextHeight = 13
  object __p_bottom: TPanel
    Left = 0
    Top = 292
    Width = 532
    Height = 41
    Align = alBottom
    BevelInner = bvRaised
    BevelOuter = bvLowered
    TabOrder = 0
    object __bytes: TLabel
      Left = 16
      Top = 16
      Width = 3
      Height = 13
    end
    object __b_cancel: TButton
      Left = 449
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Cancel'
      TabOrder = 0
      OnClick = __b_cancelClick
    end
    object __b_ok: TButton
      Left = 369
      Top = 8
      Width = 75
      Height = 25
      Anchors = [akTop, akRight]
      Caption = 'Ok'
      Enabled = False
      TabOrder = 1
      OnClick = __b_okClick
    end
  end
  object __p_center: TPanel
    Left = 0
    Top = 0
    Width = 532
    Height = 292
    Align = alClient
    BevelOuter = bvNone
    TabOrder = 1
    object __m_display: TMemo
      Left = 0
      Top = 0
      Width = 532
      Height = 292
      Align = alClient
      ReadOnly = True
      ScrollBars = ssVertical
      TabOrder = 0
    end
  end
end
