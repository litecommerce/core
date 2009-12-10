object add_shop: Tadd_shop
  Left = 294
  Top = 309
  Width = 553
  Height = 364
  Caption = 'Add new shop'
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  PixelsPerInch = 96
  TextHeight = 13
  inline add_shop_frm1: Tadd_shop_frm
    Width = 545
    Height = 337
    Align = alClient
    inherited Panel1: TPanel
      Width = 545
      Height = 337
      inherited Panel3: TPanel
        Top = 296
        Width = 545
        inherited shop_save: TButton
          OnClick = nil
        end
      end
      inherited Panel2: TPanel
        Width = 545
        Height = 296
        inherited ftp_settings: TGroupBox
          Height = 194
        end
        inherited mysql_settings: TGroupBox
          Width = 288
          Height = 194
          inherited mysql_host: TEdit
            Width = 172
          end
          inherited mysql_db: TEdit
            Width = 172
          end
          inherited mysql_login: TEdit
            Width = 172
          end
          inherited mysql_password: TEdit
            Width = 172
          end
        end
        inherited shop_settings: TGroupBox
          Top = 194
          Width = 545
        end
      end
    end
  end
end
