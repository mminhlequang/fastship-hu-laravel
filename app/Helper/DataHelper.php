<?php

namespace App\Helper;

class DataHelper
{
    public static function getCountryCode()
    {
        $countries = [
            [
                "name" => "Hungary",
                "code" => "HU",
                "emoji" => "🇭🇺",
                "unicode" => "U+1F1ED U+1F1FA",
                "image" => "HU.svg",
                "dial_code" => "+36"
            ],
            [
                "name" => "Vietnam",
                "code" => "VN",
                "emoji" => "🇻🇳",
                "unicode" => "U+1F1FB U+1F1F3",
                "image" => "VN.svg",
                "dial_code" => "+84"
            ]
        ];


//        $countries = [
//            [
//                "name" => "Andorra",
//                "code" => "AD",
//                "emoji" => "🇦🇩",
//                "unicode" => "U+1F1E6 U+1F1E9",
//                "image" => "AD.svg",
//                "dial_code" => "+376"
//            ],
//            [
//                "name" => "United Arab Emirates",
//                "code" => "AE",
//                "emoji" => "🇦🇪",
//                "unicode" => "U+1F1E6 U+1F1EA",
//                "image" => "AE.svg",
//                "dial_code" => "+971"
//            ],
//            [
//                "name" => "Afghanistan",
//                "code" => "AF",
//                "emoji" => "🇦🇫",
//                "unicode" => "U+1F1E6 U+1F1EB",
//                "image" => "AF.svg",
//                "dial_code" => "+93"
//            ],
//            [
//                "name" => "Antigua & Barbuda",
//                "code" => "AG",
//                "emoji" => "🇦🇬",
//                "unicode" => "U+1F1E6 U+1F1EC",
//                "image" => "AG.svg",
//                "dial_code" => "+1268"
//            ],
//            [
//                "name" => "Anguilla",
//                "code" => "AI",
//                "emoji" => "🇦🇮",
//                "unicode" => "U+1F1E6 U+1F1EE",
//                "image" => "AI.svg",
//                "dial_code" => "+1264"
//            ],
//            [
//                "name" => "Albania",
//                "code" => "AL",
//                "emoji" => "🇦🇱",
//                "unicode" => "U+1F1E6 U+1F1F1",
//                "image" => "AL.svg",
//                "dial_code" => "+355"
//            ],
//            [
//                "name" => "Armenia",
//                "code" => "AM",
//                "emoji" => "🇦🇲",
//                "unicode" => "U+1F1E6 U+1F1F2",
//                "image" => "AM.svg",
//                "dial_code" => "+374"
//            ],
//            [
//                "name" => "Angola",
//                "code" => "AO",
//                "emoji" => "🇦🇴",
//                "unicode" => "U+1F1E6 U+1F1F4",
//                "image" => "AO.svg",
//                "dial_code" => "+244"
//            ],
//            [
//                "name" => "Antarctica",
//                "code" => "AQ",
//                "emoji" => "🇦🇶",
//                "unicode" => "U+1F1E6 U+1F1F6",
//                "image" => "AQ.svg",
//                "dial_code" => "+672"
//            ],
//            [
//                "name" => "Argentina",
//                "code" => "AR",
//                "emoji" => "🇦🇷",
//                "unicode" => "U+1F1E6 U+1F1F7",
//                "image" => "AR.svg",
//                "dial_code" => "+54"
//            ],
//            [
//                "name" => "American Samoa",
//                "code" => "AS",
//                "emoji" => "🇦🇸",
//                "unicode" => "U+1F1E6 U+1F1F8",
//                "image" => "AS.svg",
//                "dial_code" => "+1684"
//            ],
//            [
//                "name" => "Austria",
//                "code" => "AT",
//                "emoji" => "🇦🇹",
//                "unicode" => "U+1F1E6 U+1F1F9",
//                "image" => "AT.svg",
//                "dial_code" => "+43"
//            ],
//            [
//                "name" => "Australia",
//                "code" => "AU",
//                "emoji" => "🇦🇺",
//                "unicode" => "U+1F1E6 U+1F1FA",
//                "image" => "AU.svg",
//                "dial_code" => "+61"
//            ],
//            [
//                "name" => "Aruba",
//                "code" => "AW",
//                "emoji" => "🇦🇼",
//                "unicode" => "U+1F1E6 U+1F1FC",
//                "image" => "AW.svg",
//                "dial_code" => "+297"
//            ],
//            [
//                "name" => "Åland Islands",
//                "code" => "AX",
//                "emoji" => "🇦🇽",
//                "unicode" => "U+1F1E6 U+1F1FD",
//                "image" => "AX.svg",
//                "dial_code" => "+358"
//            ],
//            [
//                "name" => "Azerbaijan",
//                "code" => "AZ",
//                "emoji" => "🇦🇿",
//                "unicode" => "U+1F1E6 U+1F1FF",
//                "image" => "AZ.svg",
//                "dial_code" => "+994"
//            ],
//            [
//                "name" => "Bosnia & Herzegovina",
//                "code" => "BA",
//                "emoji" => "🇧🇦",
//                "unicode" => "U+1F1E7 U+1F1E6",
//                "image" => "BA.svg",
//                "dial_code" => "+387"
//            ],
//            [
//                "name" => "Barbados",
//                "code" => "BB",
//                "emoji" => "🇧🇧",
//                "unicode" => "U+1F1E7 U+1F1E7",
//                "image" => "BB.svg",
//                "dial_code" => "+1246"
//            ],
//            [
//                "name" => "Bangladesh",
//                "code" => "BD",
//                "emoji" => "🇧🇩",
//                "unicode" => "U+1F1E7 U+1F1E9",
//                "image" => "BD.svg",
//                "dial_code" => "+880"
//            ],
//            [
//                "name" => "Belgium",
//                "code" => "BE",
//                "emoji" => "🇧🇪",
//                "unicode" => "U+1F1E7 U+1F1EA",
//                "image" => "BE.svg",
//                "dial_code" => "+32"
//            ],
//            [
//                "name" => "Burkina Faso",
//                "code" => "BF",
//                "emoji" => "🇧🇫",
//                "unicode" => "U+1F1E7 U+1F1EB",
//                "image" => "BF.svg",
//                "dial_code" => "+226"
//            ],
//            [
//                "name" => "Bulgaria",
//                "code" => "BG",
//                "emoji" => "🇧🇬",
//                "unicode" => "U+1F1E7 U+1F1EC",
//                "image" => "BG.svg",
//                "dial_code" => "+359"
//            ],
//            [
//                "name" => "Bahrain",
//                "code" => "BH",
//                "emoji" => "🇧🇭",
//                "unicode" => "U+1F1E7 U+1F1ED",
//                "image" => "BH.svg",
//                "dial_code" => "+973"
//            ],
//            [
//                "name" => "Burundi",
//                "code" => "BI",
//                "emoji" => "🇧🇮",
//                "unicode" => "U+1F1E7 U+1F1EE",
//                "image" => "BI.svg",
//                "dial_code" => "+257"
//            ],
//            [
//                "name" => "Benin",
//                "code" => "BJ",
//                "emoji" => "🇧🇯",
//                "unicode" => "U+1F1E7 U+1F1EF",
//                "image" => "BJ.svg",
//                "dial_code" => "+229"
//            ],
//            [
//                "name" => "St. Barthélemy",
//                "code" => "BL",
//                "emoji" => "🇧🇱",
//                "unicode" => "U+1F1E7 U+1F1F1",
//                "image" => "BL.svg",
//                "dial_code" => "+590"
//            ],
//            [
//                "name" => "Bermuda",
//                "code" => "BM",
//                "emoji" => "🇧🇲",
//                "unicode" => "U+1F1E7 U+1F1F2",
//                "image" => "BM.svg",
//                "dial_code" => "+1441"
//            ],
//            [
//                "name" => "Brunei",
//                "code" => "BN",
//                "emoji" => "🇧🇳",
//                "unicode" => "U+1F1E7 U+1F1F3",
//                "image" => "BN.svg",
//                "dial_code" => "+673"
//            ],
//            [
//                "name" => "Bolivia",
//                "code" => "BO",
//                "emoji" => "🇧🇴",
//                "unicode" => "U+1F1E7 U+1F1F4",
//                "image" => "BO.svg",
//                "dial_code" => "+591"
//            ],
//            [
//                "name" => "Brazil",
//                "code" => "BR",
//                "emoji" => "🇧🇷",
//                "unicode" => "U+1F1E7 U+1F1F7",
//                "image" => "BR.svg",
//                "dial_code" => "+55"
//            ],
//            [
//                "name" => "Bahamas",
//                "code" => "BS",
//                "emoji" => "🇧🇸",
//                "unicode" => "U+1F1E7 U+1F1F8",
//                "image" => "BS.svg",
//                "dial_code" => "+1242"
//            ],
//            [
//                "name" => "Bhutan",
//                "code" => "BT",
//                "emoji" => "🇧🇹",
//                "unicode" => "U+1F1E7 U+1F1F9",
//                "image" => "BT.svg",
//                "dial_code" => "+975"
//            ],
//            [
//                "name" => "Botswana",
//                "code" => "BW",
//                "emoji" => "🇧🇼",
//                "unicode" => "U+1F1E7 U+1F1FC",
//                "image" => "BW.svg",
//                "dial_code" => "+267"
//            ],
//            [
//                "name" => "Belarus",
//                "code" => "BY",
//                "emoji" => "🇧🇾",
//                "unicode" => "U+1F1E7 U+1F1FE",
//                "image" => "BY.svg",
//                "dial_code" => "+375"
//            ],
//            [
//                "name" => "Belize",
//                "code" => "BZ",
//                "emoji" => "🇧🇿",
//                "unicode" => "U+1F1E7 U+1F1FF",
//                "image" => "BZ.svg",
//                "dial_code" => "+501"
//            ],
//            [
//                "name" => "Canada",
//                "code" => "CA",
//                "emoji" => "🇨🇦",
//                "unicode" => "U+1F1E8 U+1F1E6",
//                "image" => "CA.svg",
//                "dial_code" => "+1"
//            ],
//            [
//                "name" => "Cocos (Keeling) Islands",
//                "code" => "CC",
//                "emoji" => "🇨🇨",
//                "unicode" => "U+1F1E8 U+1F1E8",
//                "image" => "CC.svg",
//                "dial_code" => "+61"
//            ],
//            [
//                "name" => "Congo - Kinshasa",
//                "code" => "CD",
//                "emoji" => "🇨🇩",
//                "unicode" => "U+1F1E8 U+1F1E9",
//                "image" => "CD.svg",
//                "dial_code" => "+243"
//            ],
//            [
//                "name" => "Central African Republic",
//                "code" => "CF",
//                "emoji" => "🇨🇫",
//                "unicode" => "U+1F1E8 U+1F1EB",
//                "image" => "CF.svg",
//                "dial_code" => "+236"
//            ],
//            [
//                "name" => "Congo - Brazzaville",
//                "code" => "CG",
//                "emoji" => "🇨🇬",
//                "unicode" => "U+1F1E8 U+1F1EC",
//                "image" => "CG.svg",
//                "dial_code" => "+242"
//            ],
//            [
//                "name" => "Switzerland",
//                "code" => "CH",
//                "emoji" => "🇨🇭",
//                "unicode" => "U+1F1E8 U+1F1ED",
//                "image" => "CH.svg",
//                "dial_code" => "+41"
//            ],
//            [
//                "name" => "Côte d’Ivoire",
//                "code" => "CI",
//                "emoji" => "🇨🇮",
//                "unicode" => "U+1F1E8 U+1F1EE",
//                "image" => "CI.svg",
//                "dial_code" => "+225"
//            ],
//            [
//                "name" => "Cook Islands",
//                "code" => "CK",
//                "emoji" => "🇨🇰",
//                "unicode" => "U+1F1E8 U+1F1F0",
//                "image" => "CK.svg",
//                "dial_code" => "+682"
//            ],
//            [
//                "name" => "Chile",
//                "code" => "CL",
//                "emoji" => "🇨🇱",
//                "unicode" => "U+1F1E8 U+1F1F1",
//                "image" => "CL.svg",
//                "dial_code" => "+56"
//            ],
//            [
//                "name" => "Cameroon",
//                "code" => "CM",
//                "emoji" => "🇨🇲",
//                "unicode" => "U+1F1E8 U+1F1F2",
//                "image" => "CM.svg",
//                "dial_code" => "+237"
//            ],
//            [
//                "name" => "China",
//                "code" => "CN",
//                "emoji" => "🇨🇳",
//                "unicode" => "U+1F1E8 U+1F1F3",
//                "image" => "CN.svg",
//                "dial_code" => "+86"
//            ],
//            [
//                "name" => "Colombia",
//                "code" => "CO",
//                "emoji" => "🇨🇴",
//                "unicode" => "U+1F1E8 U+1F1F4",
//                "image" => "CO.svg",
//                "dial_code" => "+57"
//            ],
//            [
//                "name" => "Costa Rica",
//                "code" => "CR",
//                "emoji" => "🇨🇷",
//                "unicode" => "U+1F1E8 U+1F1F7",
//                "image" => "CR.svg",
//                "dial_code" => "+506"
//            ],
//            [
//                "name" => "Cuba",
//                "code" => "CU",
//                "emoji" => "🇨🇺",
//                "unicode" => "U+1F1E8 U+1F1FA",
//                "image" => "CU.svg",
//                "dial_code" => "+53"
//            ],
//            [
//                "name" => "Cape Verde",
//                "code" => "CV",
//                "emoji" => "🇨🇻",
//                "unicode" => "U+1F1E8 U+1F1FB",
//                "image" => "CV.svg",
//                "dial_code" => "+238"
//            ],
//            [
//                "name" => "Christmas Island",
//                "code" => "CX",
//                "emoji" => "🇨🇽",
//                "unicode" => "U+1F1E8 U+1F1FD",
//                "image" => "CX.svg",
//                "dial_code" => "+61"
//            ],
//            [
//                "name" => "Cyprus",
//                "code" => "CY",
//                "emoji" => "🇨🇾",
//                "unicode" => "U+1F1E8 U+1F1FE",
//                "image" => "CY.svg",
//                "dial_code" => "+357"
//            ],
//            [
//                "name" => "Czechia",
//                "code" => "CZ",
//                "emoji" => "🇨🇿",
//                "unicode" => "U+1F1E8 U+1F1FF",
//                "image" => "CZ.svg",
//                "dial_code" => "+420"
//            ],
//            [
//                "name" => "Germany",
//                "code" => "DE",
//                "emoji" => "🇩🇪",
//                "unicode" => "U+1F1E9 U+1F1EA",
//                "image" => "DE.svg",
//                "dial_code" => "+49"
//            ],
//            [
//                "name" => "Djibouti",
//                "code" => "DJ",
//                "emoji" => "🇩🇯",
//                "unicode" => "U+1F1E9 U+1F1EF",
//                "image" => "DJ.svg",
//                "dial_code" => "+253"
//            ],
//            [
//                "name" => "Denmark",
//                "code" => "DK",
//                "emoji" => "🇩🇰",
//                "unicode" => "U+1F1E9 U+1F1F0",
//                "image" => "DK.svg",
//                "dial_code" => "+45"
//            ],
//            [
//                "name" => "Dominica",
//                "code" => "DM",
//                "emoji" => "🇩🇲",
//                "unicode" => "U+1F1E9 U+1F1F2",
//                "image" => "DM.svg",
//                "dial_code" => "+1767"
//            ],
//            [
//                "name" => "Dominican Republic",
//                "code" => "DO",
//                "emoji" => "🇩🇴",
//                "unicode" => "U+1F1E9 U+1F1F4",
//                "image" => "DO.svg",
//                "dial_code" => "+1849"
//            ],
//            [
//                "name" => "Algeria",
//                "code" => "DZ",
//                "emoji" => "🇩🇿",
//                "unicode" => "U+1F1E9 U+1F1FF",
//                "image" => "DZ.svg",
//                "dial_code" => "+213"
//            ],
//            [
//                "name" => "Ecuador",
//                "code" => "EC",
//                "emoji" => "🇪🇨",
//                "unicode" => "U+1F1EA U+1F1E8",
//                "image" => "EC.svg",
//                "dial_code" => "+593"
//            ],
//            [
//                "name" => "Estonia",
//                "code" => "EE",
//                "emoji" => "🇪🇪",
//                "unicode" => "U+1F1EA U+1F1EA",
//                "image" => "EE.svg",
//                "dial_code" => "+372"
//            ],
//            [
//                "name" => "Egypt",
//                "code" => "EG",
//                "emoji" => "🇪🇬",
//                "unicode" => "U+1F1EA U+1F1EC",
//                "image" => "EG.svg",
//                "dial_code" => "+20"
//            ],
//            [
//                "name" => "Eritrea",
//                "code" => "ER",
//                "emoji" => "🇪🇷",
//                "unicode" => "U+1F1EA U+1F1F7",
//                "image" => "ER.svg",
//                "dial_code" => "+291"
//            ],
//            [
//                "name" => "Spain",
//                "code" => "ES",
//                "emoji" => "🇪🇸",
//                "unicode" => "U+1F1EA U+1F1F8",
//                "image" => "ES.svg",
//                "dial_code" => "+34"
//            ],
//            [
//                "name" => "Ethiopia",
//                "code" => "ET",
//                "emoji" => "🇪🇹",
//                "unicode" => "U+1F1EA U+1F1F9",
//                "image" => "ET.svg",
//                "dial_code" => "+251"
//            ],
//            [
//                "name" => "Finland",
//                "code" => "FI",
//                "emoji" => "🇫🇮",
//                "unicode" => "U+1F1EB U+1F1EE",
//                "image" => "FI.svg",
//                "dial_code" => "+358"
//            ],
//            [
//                "name" => "Fiji",
//                "code" => "FJ",
//                "emoji" => "🇫🇯",
//                "unicode" => "U+1F1EB U+1F1EF",
//                "image" => "FJ.svg",
//                "dial_code" => "+679"
//            ],
//            [
//                "name" => "Falkland Islands",
//                "code" => "FK",
//                "emoji" => "🇫🇰",
//                "unicode" => "U+1F1EB U+1F1F0",
//                "image" => "FK.svg",
//                "dial_code" => "+500"
//            ],
//            [
//                "name" => "Micronesia",
//                "code" => "FM",
//                "emoji" => "🇫🇲",
//                "unicode" => "U+1F1EB U+1F1F2",
//                "image" => "FM.svg",
//                "dial_code" => "+691"
//            ],
//            [
//                "name" => "Faroe Islands",
//                "code" => "FO",
//                "emoji" => "🇫🇴",
//                "unicode" => "U+1F1EB U+1F1F4",
//                "image" => "FO.svg",
//                "dial_code" => "+298"
//            ],
//            [
//                "name" => "France",
//                "code" => "FR",
//                "emoji" => "🇫🇷",
//                "unicode" => "U+1F1EB U+1F1F7",
//                "image" => "FR.svg",
//                "dial_code" => "+33"
//            ],
//            [
//                "name" => "Gabon",
//                "code" => "GA",
//                "emoji" => "🇬🇦",
//                "unicode" => "U+1F1EC U+1F1E6",
//                "image" => "GA.svg",
//                "dial_code" => "+241"
//            ],
//            [
//                "name" => "United Kingdom",
//                "code" => "GB",
//                "emoji" => "🇬🇧",
//                "unicode" => "U+1F1EC U+1F1E7",
//                "image" => "GB.svg",
//                "dial_code" => "+44"
//            ],
//            [
//                "name" => "Grenada",
//                "code" => "GD",
//                "emoji" => "🇬🇩",
//                "unicode" => "U+1F1EC U+1F1E9",
//                "image" => "GD.svg",
//                "dial_code" => "+1473"
//            ],
//            [
//                "name" => "Georgia",
//                "code" => "GE",
//                "emoji" => "🇬🇪",
//                "unicode" => "U+1F1EC U+1F1EA",
//                "image" => "GE.svg",
//                "dial_code" => "+995"
//            ],
//            [
//                "name" => "French Guiana",
//                "code" => "GF",
//                "emoji" => "🇬🇫",
//                "unicode" => "U+1F1EC U+1F1EB",
//                "image" => "GF.svg",
//                "dial_code" => "+594"
//            ],
//            [
//                "name" => "Guernsey",
//                "code" => "GG",
//                "emoji" => "🇬🇬",
//                "unicode" => "U+1F1EC U+1F1EC",
//                "image" => "GG.svg",
//                "dial_code" => "+44"
//            ],
//            [
//                "name" => "Ghana",
//                "code" => "GH",
//                "emoji" => "🇬🇭",
//                "unicode" => "U+1F1EC U+1F1ED",
//                "image" => "GH.svg",
//                "dial_code" => "+233"
//            ],
//            [
//                "name" => "Gibraltar",
//                "code" => "GI",
//                "emoji" => "🇬🇮",
//                "unicode" => "U+1F1EC U+1F1EE",
//                "image" => "GI.svg",
//                "dial_code" => "+350"
//            ],
//            [
//                "name" => "Greenland",
//                "code" => "GL",
//                "emoji" => "🇬🇱",
//                "unicode" => "U+1F1EC U+1F1F1",
//                "image" => "GL.svg",
//                "dial_code" => "+299"
//            ],
//            [
//                "name" => "Gambia",
//                "code" => "GM",
//                "emoji" => "🇬🇲",
//                "unicode" => "U+1F1EC U+1F1F2",
//                "image" => "GM.svg",
//                "dial_code" => "+220"
//            ],
//            [
//                "name" => "Guinea",
//                "code" => "GN",
//                "emoji" => "🇬🇳",
//                "unicode" => "U+1F1EC U+1F1F3",
//                "image" => "GN.svg",
//                "dial_code" => "+224"
//            ],
//            [
//                "name" => "Guadeloupe",
//                "code" => "GP",
//                "emoji" => "🇬🇵",
//                "unicode" => "U+1F1EC U+1F1F5",
//                "image" => "GP.svg",
//                "dial_code" => "+590"
//            ],
//            [
//                "name" => "Equatorial Guinea",
//                "code" => "GQ",
//                "emoji" => "🇬🇶",
//                "unicode" => "U+1F1EC U+1F1F6",
//                "image" => "GQ.svg",
//                "dial_code" => "+240"
//            ],
//            [
//                "name" => "Greece",
//                "code" => "GR",
//                "emoji" => "🇬🇷",
//                "unicode" => "U+1F1EC U+1F1F7",
//                "image" => "GR.svg",
//                "dial_code" => "+30"
//            ],
//            [
//                "name" => "South Georgia & South Sandwich Islands",
//                "code" => "GS",
//                "emoji" => "🇬🇸",
//                "unicode" => "U+1F1EC U+1F1F8",
//                "image" => "GS.svg",
//                "dial_code" => "+500"
//            ],
//            [
//                "name" => "Guatemala",
//                "code" => "GT",
//                "emoji" => "🇬🇹",
//                "unicode" => "U+1F1EC U+1F1F9",
//                "image" => "GT.svg",
//                "dial_code" => "+502"
//            ],
//            [
//                "name" => "Guam",
//                "code" => "GU",
//                "emoji" => "🇬🇺",
//                "unicode" => "U+1F1EC U+1F1FA",
//                "image" => "GU.svg",
//                "dial_code" => "+1671"
//            ],
//            [
//                "name" => "Guinea-Bissau",
//                "code" => "GW",
//                "emoji" => "🇬🇼",
//                "unicode" => "U+1F1EC U+1F1FC",
//                "image" => "GW.svg",
//                "dial_code" => "+245"
//            ],
//            [
//                "name" => "Guyana",
//                "code" => "GY",
//                "emoji" => "🇬🇾",
//                "unicode" => "U+1F1EC U+1F1FE",
//                "image" => "GY.svg",
//                "dial_code" => "+595"
//            ],
//            [
//                "name" => "Hong Kong SAR China",
//                "code" => "HK",
//                "emoji" => "🇭🇰",
//                "unicode" => "U+1F1ED U+1F1F0",
//                "image" => "HK.svg",
//                "dial_code" => "+852"
//            ],
//            [
//                "name" => "Honduras",
//                "code" => "HN",
//                "emoji" => "🇭🇳",
//                "unicode" => "U+1F1ED U+1F1F3",
//                "image" => "HN.svg",
//                "dial_code" => "+504"
//            ],
//            [
//                "name" => "Croatia",
//                "code" => "HR",
//                "emoji" => "🇭🇷",
//                "unicode" => "U+1F1ED U+1F1F7",
//                "image" => "HR.svg",
//                "dial_code" => "+385"
//            ],
//            [
//                "name" => "Haiti",
//                "code" => "HT",
//                "emoji" => "🇭🇹",
//                "unicode" => "U+1F1ED U+1F1F9",
//                "image" => "HT.svg",
//                "dial_code" => "+509"
//            ],
//            [
//                "name" => "Hungary",
//                "code" => "HU",
//                "emoji" => "🇭🇺",
//                "unicode" => "U+1F1ED U+1F1FA",
//                "image" => "HU.svg",
//                "dial_code" => "+36"
//            ],
//            [
//                "name" => "Indonesia",
//                "code" => "ID",
//                "emoji" => "🇮🇩",
//                "unicode" => "U+1F1EE U+1F1E9",
//                "image" => "ID.svg",
//                "dial_code" => "+62"
//            ],
//            [
//                "name" => "Ireland",
//                "code" => "IE",
//                "emoji" => "🇮🇪",
//                "unicode" => "U+1F1EE U+1F1EA",
//                "image" => "IE.svg",
//                "dial_code" => "+353"
//            ],
//            [
//                "name" => "Israel",
//                "code" => "IL",
//                "emoji" => "🇮🇱",
//                "unicode" => "U+1F1EE U+1F1F1",
//                "image" => "IL.svg",
//                "dial_code" => "+972"
//            ],
//            [
//                "name" => "Isle of Man",
//                "code" => "IM",
//                "emoji" => "🇮🇲",
//                "unicode" => "U+1F1EE U+1F1F2",
//                "image" => "IM.svg",
//                "dial_code" => "+44"
//            ],
//            [
//                "name" => "India",
//                "code" => "IN",
//                "emoji" => "🇮🇳",
//                "unicode" => "U+1F1EE U+1F1F3",
//                "image" => "IN.svg",
//                "dial_code" => "+91"
//            ],
//            [
//                "name" => "British Indian Ocean Territory",
//                "code" => "IO",
//                "emoji" => "🇮🇴",
//                "unicode" => "U+1F1EE U+1F1F4",
//                "image" => "IO.svg",
//                "dial_code" => "+246"
//            ],
//            [
//                "name" => "Iraq",
//                "code" => "IQ",
//                "emoji" => "🇮🇶",
//                "unicode" => "U+1F1EE U+1F1F6",
//                "image" => "IQ.svg",
//                "dial_code" => "+964"
//            ],
//            [
//                "name" => "Iran",
//                "code" => "IR",
//                "emoji" => "🇮🇷",
//                "unicode" => "U+1F1EE U+1F1F7",
//                "image" => "IR.svg",
//                "dial_code" => "+98"
//            ],
//            [
//                "name" => "Iceland",
//                "code" => "IS",
//                "emoji" => "🇮🇸",
//                "unicode" => "U+1F1EE U+1F1F8",
//                "image" => "IS.svg",
//                "dial_code" => "+354"
//            ],
//            [
//                "name" => "Italy",
//                "code" => "IT",
//                "emoji" => "🇮🇹",
//                "unicode" => "U+1F1EE U+1F1F9",
//                "image" => "IT.svg",
//                "dial_code" => "+39"
//            ],
//            [
//                "name" => "Jersey",
//                "code" => "JE",
//                "emoji" => "🇯🇪",
//                "unicode" => "U+1F1EF U+1F1EA",
//                "image" => "JE.svg",
//                "dial_code" => "+44"
//            ],
//            [
//                "name" => "Jamaica",
//                "code" => "JM",
//                "emoji" => "🇯🇲",
//                "unicode" => "U+1F1EF U+1F1F2",
//                "image" => "JM.svg",
//                "dial_code" => "+1876"
//            ],
//            [
//                "name" => "Jordan",
//                "code" => "JO",
//                "emoji" => "🇯🇴",
//                "unicode" => "U+1F1EF U+1F1F4",
//                "image" => "JO.svg",
//                "dial_code" => "+962"
//            ],
//            [
//                "name" => "Japan",
//                "code" => "JP",
//                "emoji" => "🇯🇵",
//                "unicode" => "U+1F1EF U+1F1F5",
//                "image" => "JP.svg",
//                "dial_code" => "+81"
//            ],
//            [
//                "name" => "Kenya",
//                "code" => "KE",
//                "emoji" => "🇰🇪",
//                "unicode" => "U+1F1F0 U+1F1EA",
//                "image" => "KE.svg",
//                "dial_code" => "+254"
//            ],
//            [
//                "name" => "Kyrgyzstan",
//                "code" => "KG",
//                "emoji" => "🇰🇬",
//                "unicode" => "U+1F1F0 U+1F1EC",
//                "image" => "KG.svg",
//                "dial_code" => "+996"
//            ],
//            [
//                "name" => "Cambodia",
//                "code" => "KH",
//                "emoji" => "🇰🇭",
//                "unicode" => "U+1F1F0 U+1F1ED",
//                "image" => "KH.svg",
//                "dial_code" => "+855"
//            ],
//            [
//                "name" => "Kiribati",
//                "code" => "KI",
//                "emoji" => "🇰🇮",
//                "unicode" => "U+1F1F0 U+1F1EE",
//                "image" => "KI.svg",
//                "dial_code" => "+686"
//            ],
//            [
//                "name" => "Comoros",
//                "code" => "KM",
//                "emoji" => "🇰🇲",
//                "unicode" => "U+1F1F0 U+1F1F2",
//                "image" => "KM.svg",
//                "dial_code" => "+269"
//            ],
//            [
//                "name" => "St. Kitts & Nevis",
//                "code" => "KN",
//                "emoji" => "🇰🇳",
//                "unicode" => "U+1F1F0 U+1F1F3",
//                "image" => "KN.svg",
//                "dial_code" => "+1869"
//            ],
//            [
//                "name" => "North Korea",
//                "code" => "KP",
//                "emoji" => "🇰🇵",
//                "unicode" => "U+1F1F0 U+1F1F5",
//                "image" => "KP.svg",
//                "dial_code" => "+850"
//            ],
//            [
//                "name" => "South Korea",
//                "code" => "KR",
//                "emoji" => "🇰🇷",
//                "unicode" => "U+1F1F0 U+1F1F7",
//                "image" => "KR.svg",
//                "dial_code" => "+82"
//            ],
//            [
//                "name" => "Kuwait",
//                "code" => "KW",
//                "emoji" => "🇰🇼",
//                "unicode" => "U+1F1F0 U+1F1FC",
//                "image" => "KW.svg",
//                "dial_code" => "+965"
//            ],
//            [
//                "name" => "Cayman Islands",
//                "code" => "KY",
//                "emoji" => "🇰🇾",
//                "unicode" => "U+1F1F0 U+1F1FE",
//                "image" => "KY.svg",
//                "dial_code" => "+ 345"
//            ],
//            [
//                "name" => "Kazakhstan",
//                "code" => "KZ",
//                "emoji" => "🇰🇿",
//                "unicode" => "U+1F1F0 U+1F1FF",
//                "image" => "KZ.svg",
//                "dial_code" => "+77"
//            ],
//            [
//                "name" => "Laos",
//                "code" => "LA",
//                "emoji" => "🇱🇦",
//                "unicode" => "U+1F1F1 U+1F1E6",
//                "image" => "LA.svg",
//                "dial_code" => "+856"
//            ],
//            [
//                "name" => "Lebanon",
//                "code" => "LB",
//                "emoji" => "🇱🇧",
//                "unicode" => "U+1F1F1 U+1F1E7",
//                "image" => "LB.svg",
//                "dial_code" => "+961"
//            ],
//            [
//                "name" => "St. Lucia",
//                "code" => "LC",
//                "emoji" => "🇱🇨",
//                "unicode" => "U+1F1F1 U+1F1E8",
//                "image" => "LC.svg",
//                "dial_code" => "+1758"
//            ],
//            [
//                "name" => "Liechtenstein",
//                "code" => "LI",
//                "emoji" => "🇱🇮",
//                "unicode" => "U+1F1F1 U+1F1EE",
//                "image" => "LI.svg",
//                "dial_code" => "+423"
//            ],
//            [
//                "name" => "Sri Lanka",
//                "code" => "LK",
//                "emoji" => "🇱🇰",
//                "unicode" => "U+1F1F1 U+1F1F0",
//                "image" => "LK.svg",
//                "dial_code" => "+94"
//            ],
//            [
//                "name" => "Liberia",
//                "code" => "LR",
//                "emoji" => "🇱🇷",
//                "unicode" => "U+1F1F1 U+1F1F7",
//                "image" => "LR.svg",
//                "dial_code" => "+231"
//            ],
//            [
//                "name" => "Lesotho",
//                "code" => "LS",
//                "emoji" => "🇱🇸",
//                "unicode" => "U+1F1F1 U+1F1F8",
//                "image" => "LS.svg",
//                "dial_code" => "+266"
//            ],
//            [
//                "name" => "Lithuania",
//                "code" => "LT",
//                "emoji" => "🇱🇹",
//                "unicode" => "U+1F1F1 U+1F1F9",
//                "image" => "LT.svg",
//                "dial_code" => "+370"
//            ],
//            [
//                "name" => "Luxembourg",
//                "code" => "LU",
//                "emoji" => "🇱🇺",
//                "unicode" => "U+1F1F1 U+1F1FA",
//                "image" => "LU.svg",
//                "dial_code" => "+352"
//            ],
//            [
//                "name" => "Latvia",
//                "code" => "LV",
//                "emoji" => "🇱🇻",
//                "unicode" => "U+1F1F1 U+1F1FB",
//                "image" => "LV.svg",
//                "dial_code" => "+371"
//            ],
//            [
//                "name" => "Libya",
//                "code" => "LY",
//                "emoji" => "🇱🇾",
//                "unicode" => "U+1F1F1 U+1F1FE",
//                "image" => "LY.svg",
//                "dial_code" => "+218"
//            ],
//            [
//                "name" => "Morocco",
//                "code" => "MA",
//                "emoji" => "🇲🇦",
//                "unicode" => "U+1F1F2 U+1F1E6",
//                "image" => "MA.svg",
//                "dial_code" => "+212"
//            ],
//            [
//                "name" => "Monaco",
//                "code" => "MC",
//                "emoji" => "🇲🇨",
//                "unicode" => "U+1F1F2 U+1F1E8",
//                "image" => "MC.svg",
//                "dial_code" => "+377"
//            ],
//            [
//                "name" => "Moldova",
//                "code" => "MD",
//                "emoji" => "🇲🇩",
//                "unicode" => "U+1F1F2 U+1F1E9",
//                "image" => "MD.svg",
//                "dial_code" => "+373"
//            ],
//            [
//                "name" => "Montenegro",
//                "code" => "ME",
//                "emoji" => "🇲🇪",
//                "unicode" => "U+1F1F2 U+1F1EA",
//                "image" => "ME.svg",
//                "dial_code" => "+382"
//            ],
//            [
//                "name" => "St. Martin",
//                "code" => "MF",
//                "emoji" => "🇲🇫",
//                "unicode" => "U+1F1F2 U+1F1EB",
//                "image" => "MF.svg",
//                "dial_code" => "+590"
//            ],
//            [
//                "name" => "Madagascar",
//                "code" => "MG",
//                "emoji" => "🇲🇬",
//                "unicode" => "U+1F1F2 U+1F1EC",
//                "image" => "MG.svg",
//                "dial_code" => "+261"
//            ],
//            [
//                "name" => "Marshall Islands",
//                "code" => "MH",
//                "emoji" => "🇲🇭",
//                "unicode" => "U+1F1F2 U+1F1ED",
//                "image" => "MH.svg",
//                "dial_code" => "+692"
//            ],
//            [
//                "name" => "North Macedonia",
//                "code" => "MK",
//                "emoji" => "🇲🇰",
//                "unicode" => "U+1F1F2 U+1F1F0",
//                "image" => "MK.svg",
//                "dial_code" => "+389"
//            ],
//            [
//                "name" => "Mali",
//                "code" => "ML",
//                "emoji" => "🇲🇱",
//                "unicode" => "U+1F1F2 U+1F1F1",
//                "image" => "ML.svg",
//                "dial_code" => "+223"
//            ],
//            [
//                "name" => "Myanmar (Burma)",
//                "code" => "MM",
//                "emoji" => "🇲🇲",
//                "unicode" => "U+1F1F2 U+1F1F2",
//                "image" => "MM.svg",
//                "dial_code" => "+95"
//            ],
//            [
//                "name" => "Mongolia",
//                "code" => "MN",
//                "emoji" => "🇲🇳",
//                "unicode" => "U+1F1F2 U+1F1F3",
//                "image" => "MN.svg",
//                "dial_code" => "+976"
//            ],
//            [
//                "name" => "Macao SAR China",
//                "code" => "MO",
//                "emoji" => "🇲🇴",
//                "unicode" => "U+1F1F2 U+1F1F4",
//                "image" => "MO.svg",
//                "dial_code" => "+853"
//            ],
//            [
//                "name" => "Northern Mariana Islands",
//                "code" => "MP",
//                "emoji" => "🇲🇵",
//                "unicode" => "U+1F1F2 U+1F1F5",
//                "image" => "MP.svg",
//                "dial_code" => "+1670"
//            ],
//            [
//                "name" => "Martinique",
//                "code" => "MQ",
//                "emoji" => "🇲🇶",
//                "unicode" => "U+1F1F2 U+1F1F6",
//                "image" => "MQ.svg",
//                "dial_code" => "+596"
//            ],
//            [
//                "name" => "Mauritania",
//                "code" => "MR",
//                "emoji" => "🇲🇷",
//                "unicode" => "U+1F1F2 U+1F1F7",
//                "image" => "MR.svg",
//                "dial_code" => "+222"
//            ],
//            [
//                "name" => "Montserrat",
//                "code" => "MS",
//                "emoji" => "🇲🇸",
//                "unicode" => "U+1F1F2 U+1F1F8",
//                "image" => "MS.svg",
//                "dial_code" => "+1664"
//            ],
//            [
//                "name" => "Malta",
//                "code" => "MT",
//                "emoji" => "🇲🇹",
//                "unicode" => "U+1F1F2 U+1F1F9",
//                "image" => "MT.svg",
//                "dial_code" => "+356"
//            ],
//            [
//                "name" => "Mauritius",
//                "code" => "MU",
//                "emoji" => "🇲🇺",
//                "unicode" => "U+1F1F2 U+1F1FA",
//                "image" => "MU.svg",
//                "dial_code" => "+230"
//            ],
//            [
//                "name" => "Maldives",
//                "code" => "MV",
//                "emoji" => "🇲🇻",
//                "unicode" => "U+1F1F2 U+1F1FB",
//                "image" => "MV.svg",
//                "dial_code" => "+960"
//            ],
//            [
//                "name" => "Malawi",
//                "code" => "MW",
//                "emoji" => "🇲🇼",
//                "unicode" => "U+1F1F2 U+1F1FC",
//                "image" => "MW.svg",
//                "dial_code" => "+265"
//            ],
//            [
//                "name" => "Mexico",
//                "code" => "MX",
//                "emoji" => "🇲🇽",
//                "unicode" => "U+1F1F2 U+1F1FD",
//                "image" => "MX.svg",
//                "dial_code" => "+52"
//            ],
//            [
//                "name" => "Malaysia",
//                "code" => "MY",
//                "emoji" => "🇲🇾",
//                "unicode" => "U+1F1F2 U+1F1FE",
//                "image" => "MY.svg",
//                "dial_code" => "+60"
//            ],
//            [
//                "name" => "Mozambique",
//                "code" => "MZ",
//                "emoji" => "🇲🇿",
//                "unicode" => "U+1F1F2 U+1F1FF",
//                "image" => "MZ.svg",
//                "dial_code" => "+258"
//            ],
//            [
//                "name" => "Namibia",
//                "code" => "NA",
//                "emoji" => "🇳🇦",
//                "unicode" => "U+1F1F3 U+1F1E6",
//                "image" => "NA.svg",
//                "dial_code" => "+264"
//            ],
//            [
//                "name" => "New Caledonia",
//                "code" => "NC",
//                "emoji" => "🇳🇨",
//                "unicode" => "U+1F1F3 U+1F1E8",
//                "image" => "NC.svg",
//                "dial_code" => "+687"
//            ],
//            [
//                "name" => "Niger",
//                "code" => "NE",
//                "emoji" => "🇳🇪",
//                "unicode" => "U+1F1F3 U+1F1EA",
//                "image" => "NE.svg",
//                "dial_code" => "+227"
//            ],
//            [
//                "name" => "Norfolk Island",
//                "code" => "NF",
//                "emoji" => "🇳🇫",
//                "unicode" => "U+1F1F3 U+1F1EB",
//                "image" => "NF.svg",
//                "dial_code" => "+672"
//            ],
//            [
//                "name" => "Nigeria",
//                "code" => "NG",
//                "emoji" => "🇳🇬",
//                "unicode" => "U+1F1F3 U+1F1EC",
//                "image" => "NG.svg",
//                "dial_code" => "+234"
//            ],
//            [
//                "name" => "Nicaragua",
//                "code" => "NI",
//                "emoji" => "🇳🇮",
//                "unicode" => "U+1F1F3 U+1F1EE",
//                "image" => "NI.svg",
//                "dial_code" => "+505"
//            ],
//            [
//                "name" => "Netherlands",
//                "code" => "NL",
//                "emoji" => "🇳🇱",
//                "unicode" => "U+1F1F3 U+1F1F1",
//                "image" => "NL.svg",
//                "dial_code" => "+31"
//            ],
//            [
//                "name" => "Norway",
//                "code" => "NO",
//                "emoji" => "🇳🇴",
//                "unicode" => "U+1F1F3 U+1F1F4",
//                "image" => "NO.svg",
//                "dial_code" => "+47"
//            ],
//            [
//                "name" => "Nepal",
//                "code" => "NP",
//                "emoji" => "🇳🇵",
//                "unicode" => "U+1F1F3 U+1F1F5",
//                "image" => "NP.svg",
//                "dial_code" => "+977"
//            ],
//            [
//                "name" => "Nauru",
//                "code" => "NR",
//                "emoji" => "🇳🇷",
//                "unicode" => "U+1F1F3 U+1F1F7",
//                "image" => "NR.svg",
//                "dial_code" => "+674"
//            ],
//            [
//                "name" => "Niue",
//                "code" => "NU",
//                "emoji" => "🇳🇺",
//                "unicode" => "U+1F1F3 U+1F1FA",
//                "image" => "NU.svg",
//                "dial_code" => "+683"
//            ],
//            [
//                "name" => "New Zealand",
//                "code" => "NZ",
//                "emoji" => "🇳🇿",
//                "unicode" => "U+1F1F3 U+1F1FF",
//                "image" => "NZ.svg",
//                "dial_code" => "+64"
//            ],
//            [
//                "name" => "Oman",
//                "code" => "OM",
//                "emoji" => "🇴🇲",
//                "unicode" => "U+1F1F4 U+1F1F2",
//                "image" => "OM.svg",
//                "dial_code" => "+968"
//            ],
//            [
//                "name" => "Panama",
//                "code" => "PA",
//                "emoji" => "🇵🇦",
//                "unicode" => "U+1F1F5 U+1F1E6",
//                "image" => "PA.svg",
//                "dial_code" => "+507"
//            ],
//            [
//                "name" => "Peru",
//                "code" => "PE",
//                "emoji" => "🇵🇪",
//                "unicode" => "U+1F1F5 U+1F1EA",
//                "image" => "PE.svg",
//                "dial_code" => "+51"
//            ],
//            [
//                "name" => "French Polynesia",
//                "code" => "PF",
//                "emoji" => "🇵🇫",
//                "unicode" => "U+1F1F5 U+1F1EB",
//                "image" => "PF.svg",
//                "dial_code" => "+689"
//            ],
//            [
//                "name" => "Papua New Guinea",
//                "code" => "PG",
//                "emoji" => "🇵🇬",
//                "unicode" => "U+1F1F5 U+1F1EC",
//                "image" => "PG.svg",
//                "dial_code" => "+675"
//            ],
//            [
//                "name" => "Philippines",
//                "code" => "PH",
//                "emoji" => "🇵🇭",
//                "unicode" => "U+1F1F5 U+1F1ED",
//                "image" => "PH.svg",
//                "dial_code" => "+63"
//            ],
//            [
//                "name" => "Pakistan",
//                "code" => "PK",
//                "emoji" => "🇵🇰",
//                "unicode" => "U+1F1F5 U+1F1F0",
//                "image" => "PK.svg",
//                "dial_code" => "+92"
//            ],
//            [
//                "name" => "Poland",
//                "code" => "PL",
//                "emoji" => "🇵🇱",
//                "unicode" => "U+1F1F5 U+1F1F1",
//                "image" => "PL.svg",
//                "dial_code" => "+48"
//            ],
//            [
//                "name" => "St. Pierre & Miquelon",
//                "code" => "PM",
//                "emoji" => "🇵🇲",
//                "unicode" => "U+1F1F5 U+1F1F2",
//                "image" => "PM.svg",
//                "dial_code" => "+508"
//            ],
//            [
//                "name" => "Pitcairn Islands",
//                "code" => "PN",
//                "emoji" => "🇵🇳",
//                "unicode" => "U+1F1F5 U+1F1F3",
//                "image" => "PN.svg",
//                "dial_code" => "+872"
//            ],
//            [
//                "name" => "Puerto Rico",
//                "code" => "PR",
//                "emoji" => "🇵🇷",
//                "unicode" => "U+1F1F5 U+1F1F7",
//                "image" => "PR.svg",
//                "dial_code" => "+1939"
//            ],
//            [
//                "name" => "Palestinian Territories",
//                "code" => "PS",
//                "emoji" => "🇵🇸",
//                "unicode" => "U+1F1F5 U+1F1F8",
//                "image" => "PS.svg",
//                "dial_code" => "+970"
//            ],
//            [
//                "name" => "Portugal",
//                "code" => "PT",
//                "emoji" => "🇵🇹",
//                "unicode" => "U+1F1F5 U+1F1F9",
//                "image" => "PT.svg",
//                "dial_code" => "+351"
//            ],
//            [
//                "name" => "Palau",
//                "code" => "PW",
//                "emoji" => "🇵🇼",
//                "unicode" => "U+1F1F5 U+1F1FC",
//                "image" => "PW.svg",
//                "dial_code" => "+680"
//            ],
//            [
//                "name" => "Paraguay",
//                "code" => "PY",
//                "emoji" => "🇵🇾",
//                "unicode" => "U+1F1F5 U+1F1FE",
//                "image" => "PY.svg",
//                "dial_code" => "+595"
//            ],
//            [
//                "name" => "Qatar",
//                "code" => "QA",
//                "emoji" => "🇶🇦",
//                "unicode" => "U+1F1F6 U+1F1E6",
//                "image" => "QA.svg",
//                "dial_code" => "+974"
//            ],
//            [
//                "name" => "Réunion",
//                "code" => "RE",
//                "emoji" => "🇷🇪",
//                "unicode" => "U+1F1F7 U+1F1EA",
//                "image" => "RE.svg",
//                "dial_code" => "+262"
//            ],
//            [
//                "name" => "Romania",
//                "code" => "RO",
//                "emoji" => "🇷🇴",
//                "unicode" => "U+1F1F7 U+1F1F4",
//                "image" => "RO.svg",
//                "dial_code" => "+40"
//            ],
//            [
//                "name" => "Serbia",
//                "code" => "RS",
//                "emoji" => "🇷🇸",
//                "unicode" => "U+1F1F7 U+1F1F8",
//                "image" => "RS.svg",
//                "dial_code" => "+381"
//            ],
//            [
//                "name" => "Russia",
//                "code" => "RU",
//                "emoji" => "🇷🇺",
//                "unicode" => "U+1F1F7 U+1F1FA",
//                "image" => "RU.svg",
//                "dial_code" => "+7"
//            ],
//            [
//                "name" => "Rwanda",
//                "code" => "RW",
//                "emoji" => "🇷🇼",
//                "unicode" => "U+1F1F7 U+1F1FC",
//                "image" => "RW.svg",
//                "dial_code" => "+250"
//            ],
//            [
//                "name" => "Saudi Arabia",
//                "code" => "SA",
//                "emoji" => "🇸🇦",
//                "unicode" => "U+1F1F8 U+1F1E6",
//                "image" => "SA.svg",
//                "dial_code" => "+966"
//            ],
//            [
//                "name" => "Solomon Islands",
//                "code" => "SB",
//                "emoji" => "🇸🇧",
//                "unicode" => "U+1F1F8 U+1F1E7",
//                "image" => "SB.svg",
//                "dial_code" => "+677"
//            ],
//            [
//                "name" => "Seychelles",
//                "code" => "SC",
//                "emoji" => "🇸🇨",
//                "unicode" => "U+1F1F8 U+1F1E8",
//                "image" => "SC.svg",
//                "dial_code" => "+248"
//            ],
//            [
//                "name" => "Sudan",
//                "code" => "SD",
//                "emoji" => "🇸🇩",
//                "unicode" => "U+1F1F8 U+1F1E9",
//                "image" => "SD.svg",
//                "dial_code" => "+249"
//            ],
//            [
//                "name" => "Sweden",
//                "code" => "SE",
//                "emoji" => "🇸🇪",
//                "unicode" => "U+1F1F8 U+1F1EA",
//                "image" => "SE.svg",
//                "dial_code" => "+46"
//            ],
//            [
//                "name" => "Singapore",
//                "code" => "SG",
//                "emoji" => "🇸🇬",
//                "unicode" => "U+1F1F8 U+1F1EC",
//                "image" => "SG.svg",
//                "dial_code" => "+65"
//            ],
//            [
//                "name" => "St. Helena",
//                "code" => "SH",
//                "emoji" => "🇸🇭",
//                "unicode" => "U+1F1F8 U+1F1ED",
//                "image" => "SH.svg",
//                "dial_code" => "+290"
//            ],
//            [
//                "name" => "Slovenia",
//                "code" => "SI",
//                "emoji" => "🇸🇮",
//                "unicode" => "U+1F1F8 U+1F1EE",
//                "image" => "SI.svg",
//                "dial_code" => "+386"
//            ],
//            [
//                "name" => "Svalbard & Jan Mayen",
//                "code" => "SJ",
//                "emoji" => "🇸🇯",
//                "unicode" => "U+1F1F8 U+1F1EF",
//                "image" => "SJ.svg",
//                "dial_code" => "+47"
//            ],
//            [
//                "name" => "Slovakia",
//                "code" => "SK",
//                "emoji" => "🇸🇰",
//                "unicode" => "U+1F1F8 U+1F1F0",
//                "image" => "SK.svg",
//                "dial_code" => "+421"
//            ],
//            [
//                "name" => "Sierra Leone",
//                "code" => "SL",
//                "emoji" => "🇸🇱",
//                "unicode" => "U+1F1F8 U+1F1F1",
//                "image" => "SL.svg",
//                "dial_code" => "+232"
//            ],
//            [
//                "name" => "San Marino",
//                "code" => "SM",
//                "emoji" => "🇸🇲",
//                "unicode" => "U+1F1F8 U+1F1F2",
//                "image" => "SM.svg",
//                "dial_code" => "+378"
//            ],
//            [
//                "name" => "Senegal",
//                "code" => "SN",
//                "emoji" => "🇸🇳",
//                "unicode" => "U+1F1F8 U+1F1F3",
//                "image" => "SN.svg",
//                "dial_code" => "+221"
//            ],
//            [
//                "name" => "Somalia",
//                "code" => "SO",
//                "emoji" => "🇸🇴",
//                "unicode" => "U+1F1F8 U+1F1F4",
//                "image" => "SO.svg",
//                "dial_code" => "+252"
//            ],
//            [
//                "name" => "Suriname",
//                "code" => "SR",
//                "emoji" => "🇸🇷",
//                "unicode" => "U+1F1F8 U+1F1F7",
//                "image" => "SR.svg",
//                "dial_code" => "+597"
//            ],
//            [
//                "name" => "South Sudan",
//                "code" => "SS",
//                "emoji" => "🇸🇸",
//                "unicode" => "U+1F1F8 U+1F1F8",
//                "image" => "SS.svg",
//                "dial_code" => "+211"
//            ],
//            [
//                "name" => "São Tomé & Príncipe",
//                "code" => "ST",
//                "emoji" => "🇸🇹",
//                "unicode" => "U+1F1F8 U+1F1F9",
//                "image" => "ST.svg",
//                "dial_code" => "+239"
//            ],
//            [
//                "name" => "El Salvador",
//                "code" => "SV",
//                "emoji" => "🇸🇻",
//                "unicode" => "U+1F1F8 U+1F1FB",
//                "image" => "SV.svg",
//                "dial_code" => "+503"
//            ],
//            [
//                "name" => "Syria",
//                "code" => "SY",
//                "emoji" => "🇸🇾",
//                "unicode" => "U+1F1F8 U+1F1FE",
//                "image" => "SY.svg",
//                "dial_code" => "+963"
//            ],
//            [
//                "name" => "Eswatini",
//                "code" => "SZ",
//                "emoji" => "🇸🇿",
//                "unicode" => "U+1F1F8 U+1F1FF",
//                "image" => "SZ.svg",
//                "dial_code" => "+268"
//            ],
//            [
//                "name" => "Turks & Caicos Islands",
//                "code" => "TC",
//                "emoji" => "🇹🇨",
//                "unicode" => "U+1F1F9 U+1F1E8",
//                "image" => "TC.svg",
//                "dial_code" => "+1649"
//            ],
//            [
//                "name" => "Chad",
//                "code" => "TD",
//                "emoji" => "🇹🇩",
//                "unicode" => "U+1F1F9 U+1F1E9",
//                "image" => "TD.svg",
//                "dial_code" => "+235"
//            ],
//            [
//                "name" => "Togo",
//                "code" => "TG",
//                "emoji" => "🇹🇬",
//                "unicode" => "U+1F1F9 U+1F1EC",
//                "image" => "TG.svg",
//                "dial_code" => "+228"
//            ],
//            [
//                "name" => "Thailand",
//                "code" => "TH",
//                "emoji" => "🇹🇭",
//                "unicode" => "U+1F1F9 U+1F1ED",
//                "image" => "TH.svg",
//                "dial_code" => "+66"
//            ],
//            [
//                "name" => "Tajikistan",
//                "code" => "TJ",
//                "emoji" => "🇹🇯",
//                "unicode" => "U+1F1F9 U+1F1EF",
//                "image" => "TJ.svg",
//                "dial_code" => "+992"
//            ],
//            [
//                "name" => "Tokelau",
//                "code" => "TK",
//                "emoji" => "🇹🇰",
//                "unicode" => "U+1F1F9 U+1F1F0",
//                "image" => "TK.svg",
//                "dial_code" => "+690"
//            ],
//            [
//                "name" => "Timor-Leste",
//                "code" => "TL",
//                "emoji" => "🇹🇱",
//                "unicode" => "U+1F1F9 U+1F1F1",
//                "image" => "TL.svg",
//                "dial_code" => "+670"
//            ],
//            [
//                "name" => "Turkmenistan",
//                "code" => "TM",
//                "emoji" => "🇹🇲",
//                "unicode" => "U+1F1F9 U+1F1F2",
//                "image" => "TM.svg",
//                "dial_code" => "+993"
//            ],
//            [
//                "name" => "Tunisia",
//                "code" => "TN",
//                "emoji" => "🇹🇳",
//                "unicode" => "U+1F1F9 U+1F1F3",
//                "image" => "TN.svg",
//                "dial_code" => "+216"
//            ],
//            [
//                "name" => "Tonga",
//                "code" => "TO",
//                "emoji" => "🇹🇴",
//                "unicode" => "U+1F1F9 U+1F1F4",
//                "image" => "TO.svg",
//                "dial_code" => "+676"
//            ],
//            [
//                "name" => "Turkey",
//                "code" => "TR",
//                "emoji" => "🇹🇷",
//                "unicode" => "U+1F1F9 U+1F1F7",
//                "image" => "TR.svg",
//                "dial_code" => "+90"
//            ],
//            [
//                "name" => "Trinidad & Tobago",
//                "code" => "TT",
//                "emoji" => "🇹🇹",
//                "unicode" => "U+1F1F9 U+1F1F9",
//                "image" => "TT.svg",
//                "dial_code" => "+1868"
//            ],
//            [
//                "name" => "Tuvalu",
//                "code" => "TV",
//                "emoji" => "🇹🇻",
//                "unicode" => "U+1F1F9 U+1F1FB",
//                "image" => "TV.svg",
//                "dial_code" => "+688"
//            ],
//            [
//                "name" => "Taiwan",
//                "code" => "TW",
//                "emoji" => "🇹🇼",
//                "unicode" => "U+1F1F9 U+1F1FC",
//                "image" => "TW.svg",
//                "dial_code" => "+886"
//            ],
//            [
//                "name" => "Tanzania",
//                "code" => "TZ",
//                "emoji" => "🇹🇿",
//                "unicode" => "U+1F1F9 U+1F1FF",
//                "image" => "TZ.svg",
//                "dial_code" => "+255"
//            ],
//            [
//                "name" => "Ukraine",
//                "code" => "UA",
//                "emoji" => "🇺🇦",
//                "unicode" => "U+1F1FA U+1F1E6",
//                "image" => "UA.svg",
//                "dial_code" => "+380"
//            ],
//            [
//                "name" => "Uganda",
//                "code" => "UG",
//                "emoji" => "🇺🇬",
//                "unicode" => "U+1F1FA U+1F1EC",
//                "image" => "UG.svg",
//                "dial_code" => "+256"
//            ],
//            [
//                "name" => "United States",
//                "code" => "US",
//                "emoji" => "🇺🇸",
//                "unicode" => "U+1F1FA U+1F1F8",
//                "image" => "US.svg",
//                "dial_code" => "+1"
//            ],
//            [
//                "name" => "Uruguay",
//                "code" => "UY",
//                "emoji" => "🇺🇾",
//                "unicode" => "U+1F1FA U+1F1FE",
//                "image" => "UY.svg",
//                "dial_code" => "+598"
//            ],
//            [
//                "name" => "Uzbekistan",
//                "code" => "UZ",
//                "emoji" => "🇺🇿",
//                "unicode" => "U+1F1FA U+1F1FF",
//                "image" => "UZ.svg",
//                "dial_code" => "+998"
//            ],
//            [
//                "name" => "Vatican City",
//                "code" => "VA",
//                "emoji" => "🇻🇦",
//                "unicode" => "U+1F1FB U+1F1E6",
//                "image" => "VA.svg",
//                "dial_code" => "+379"
//            ],
//            [
//                "name" => "St. Vincent & Grenadines",
//                "code" => "VC",
//                "emoji" => "🇻🇨",
//                "unicode" => "U+1F1FB U+1F1E8",
//                "image" => "VC.svg",
//                "dial_code" => "+1784"
//            ],
//            [
//                "name" => "Venezuela",
//                "code" => "VE",
//                "emoji" => "🇻🇪",
//                "unicode" => "U+1F1FB U+1F1EA",
//                "image" => "VE.svg",
//                "dial_code" => "+58"
//            ],
//            [
//                "name" => "British Virgin Islands",
//                "code" => "VG",
//                "emoji" => "🇻🇬",
//                "unicode" => "U+1F1FB U+1F1EC",
//                "image" => "VG.svg",
//                "dial_code" => "+1284"
//            ],
//            [
//                "name" => "U.S. Virgin Islands",
//                "code" => "VI",
//                "emoji" => "🇻🇮",
//                "unicode" => "U+1F1FB U+1F1EE",
//                "image" => "VI.svg",
//                "dial_code" => "+1340"
//            ],
//            [
//                "name" => "Vietnam",
//                "code" => "VN",
//                "emoji" => "🇻🇳",
//                "unicode" => "U+1F1FB U+1F1F3",
//                "image" => "VN.svg",
//                "dial_code" => "+84"
//            ],
//            [
//                "name" => "Vanuatu",
//                "code" => "VU",
//                "emoji" => "🇻🇺",
//                "unicode" => "U+1F1FB U+1F1FA",
//                "image" => "VU.svg",
//                "dial_code" => "+678"
//            ],
//            [
//                "name" => "Wallis & Futuna",
//                "code" => "WF",
//                "emoji" => "🇼🇫",
//                "unicode" => "U+1F1FC U+1F1EB",
//                "image" => "WF.svg",
//                "dial_code" => "+681"
//            ],
//            [
//                "name" => "Samoa",
//                "code" => "WS",
//                "emoji" => "🇼🇸",
//                "unicode" => "U+1F1FC U+1F1F8",
//                "image" => "WS.svg",
//                "dial_code" => "+685"
//            ],
//            [
//                "name" => "Yemen",
//                "code" => "YE",
//                "emoji" => "🇾🇪",
//                "unicode" => "U+1F1FE U+1F1EA",
//                "image" => "YE.svg",
//                "dial_code" => "+967"
//            ],
//            [
//                "name" => "Mayotte",
//                "code" => "YT",
//                "emoji" => "🇾🇹",
//                "unicode" => "U+1F1FE U+1F1F9",
//                "image" => "YT.svg",
//                "dial_code" => "+262"
//            ],
//            [
//                "name" => "South Africa",
//                "code" => "ZA",
//                "emoji" => "🇿🇦",
//                "unicode" => "U+1F1FF U+1F1E6",
//                "image" => "ZA.svg",
//                "dial_code" => "+27"
//            ],
//            [
//                "name" => "Zambia",
//                "code" => "ZM",
//                "emoji" => "🇿🇲",
//                "unicode" => "U+1F1FF U+1F1F2",
//                "image" => "ZM.svg",
//                "dial_code" => "+260"
//            ],
//            [
//                "name" => "Zimbabwe",
//                "code" => "ZW",
//                "emoji" => "🇿🇼",
//                "unicode" => "U+1F1FF U+1F1FC",
//                "image" => "ZW.svg",
//                "dial_code" => "+263"
//            ]
//        ];

        return $countries;
    }

    public static function getListDayInMonth()
    {
        $arrDay = [];
        $month = date('m');
        $year = date('Y');
        // lấy tất cả ngày trong tháng
        for ($day = 1; $day <= 31; $day++) {
            $time = mktime('12', '0', '0', $month, $day, $year);
            if (date('m', $time) == $month)
                $arrDay[] = date('Y-m-d', $time);
        }
        return $arrDay;
    }

    public static function getListMonthInYear()
    {
        // $arrMonth = [];
        // for ($m=1; $m<=12; $m++) {
        //     $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
        //      $arrMonth[] = date('Y-m-d', $month);
        //     }


        $arrMonth = [];
        $year = date('Y');
        // lấy tất cả ngày trong tháng
        for ($month = 1; $month <= 13; $month++) {
            $time = mktime('0', '0', '0', $month, 0, $year);
            if (date('Y', $time) == $year)
                $arrMonth[] = date('Y-m-d', $time);
        }
        return $arrMonth;
    }
}