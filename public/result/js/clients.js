var salesProcessStepNumb = 0,
	nextdate = 0,
	cancelMembPopoverOpt = {
		html: true,
		content: "<a class='btn btn-default m-r-10 cancel-delete-event' href='#'><i class='fa fa-times'></i> No</a> <a class='btn btn-red' href='#' id='cancelMemb'><i class='glyphicon glyphicon-trash'></i> Yes</a>",
//		container: popoverContainer,
		title: "<strong>Cancel this membership?</strong>",
		trigger: 'manual'
	};

function toggleNotes(elem){
	var notesRow = $(elem).closest('.form-group').next();
	if($(elem).val() == 'Yes')
		notesRow.removeClass('hidden');
	else
		notesRow.addClass('hidden');
}
function toggleSmokeDays(){
	var smokeDd = $('select[name="smoking"]');
	var smokeAmnt = $('#smokeAmnt');
	if(smokeDd.val() == 'Yes')
		$(smokeAmnt).removeClass('hidden');
	else
		$(smokeAmnt).addClass('hidden');
}
// function toggleReference(val){
// 	var refNet = 'input[name="referralNetwork"]';
// 	var clientList = $('#clientList');
// 	var staffList = $('#staffList');
// 	var proList = $('#proList');
// 	if(!val)
// 		val = $(refNet+':checked').val();
	
// 	if(val == 'Client'){
// 		clientList.removeClass('hidden');
// 		staffList.addClass('hidden');
// 		proList.addClass('hidden');
// 	}
// 	else if(val == 'Staff'){
// 		staffList.removeClass('hidden');
// 		proList.addClass('hidden');
// 		clientList.addClass('hidden');
// 	}
// 	else if(val == 'Professional network'){
// 		proList.removeClass('hidden');
// 		clientList.addClass('hidden');
// 		staffList.addClass('hidden');
// 	}
// 	else{
// 		clientList.addClass('hidden');
// 		proList.addClass('hidden');
// 		staffList.addClass('hidden');
// 	}
// }

function toggleReference(val){
	var refNet = 'input[name="referralNetwork"]';
	var clientList = $('#clientList');
	var staffList = $('#staffList');
	var proList = $('#proList');
	var referralName = $('#referralName');

	if(!val)
		val = $(refNet+':checked').val();
	
	if(val == 'Client'){
		// clientList.removeClass('hidden');
		referralName.removeClass('hidden');
		clientList.addClass('hidden');

		staffList.addClass('hidden');
		proList.addClass('hidden');
	}
	else if(val == 'Staff'){
		// staffList.removeClass('hidden');
		referralName.removeClass('hidden');
		
		staffList.addClass('hidden');
		proList.addClass('hidden');
		clientList.addClass('hidden');
	}
	else if(val == 'Professional network'){
		// proList.removeClass('hidden');
		referralName.removeClass('hidden');
		proList.addClass('hidden');
		clientList.addClass('hidden');
		staffList.addClass('hidden');
	}
	else{
		referralName.addClass('hidden');
		clientList.addClass('hidden');
		proList.addClass('hidden');
		staffList.addClass('hidden');
	}
}

function toggleMeasureDd(measure, elem, init){
	var metricDd, ImpDd, currUnit, cmVal, feetVal, inchVal, ImpToMet, metToImp;
	if(measure == 'height'){
		metricDd = $('select#metricHeight');
		ImpDd = $('select#imperialHeight');
		currUnit = $('input[name="heightUnit"]');
		cmVal = metricDd.find(":selected").data('value');
		feetVal = ImpDd.find(":selected").data('feet');
		inchVal = ImpDd.find(":selected").data('inch');
		if(init){
			if(currUnit.val() == 'Metric' || currUnit.val() == ''){
				ImpDd.selectpicker('hide');
				elem.text('Show Imperial');
				ImpDd.prop("disabled", true);
			}
			else{
				metricDd.selectpicker('hide');
				elem.text('Show Metric');
				metricDd.prop("disabled", true);
			}
			return;
		}
	}
	else if(measure == 'weight'){
		metricDd = $('select#metricWeight');
		ImpDd = $('select#imperialWeight');
		currUnit = $('input[name="weightUnit"]');
		kgVal = metricDd.find(":selected").data('value');
		poundVal = ImpDd.find(":selected").data('value');
		if(init){
			if(currUnit.val() == 'Metric' || currUnit.val() == ''){
				ImpDd.selectpicker('hide');
				elem.text('Show Imperial');
				ImpDd.prop("disabled", true);
			}
			else{
				metricDd.selectpicker('hide');
				elem.text('Show Metric');
				metricDd.prop("disabled", true);
			}
			return;
		}
	}
	if(currUnit.val() == 'Imperial'){		//switching to metric
		if(measure == 'weight')
			ImpToMet = Math.round(poundVal/2.2);
		else if(measure == 'height')
			ImpToMet = Math.round((feetVal*30)+(inchVal*2.5));

		metricDd.selectpicker('show');
		metricDd.prop("disabled", false);
		metricDd.find("option[data-value='"+ImpToMet+"']").prop('selected', true);
		metricDd.selectpicker('refresh');
		ImpDd.selectpicker('hide');
		ImpDd.prop("disabled", true);
		currUnit.val('Metric');
		elem.text('Show Imperial');
	}
	else{	//switching to imperial
		if(measure == 'weight')
			metToImp = Math.round(kgVal*2.2);
		
		else if(measure == 'height'){
			var inches = cmVal/2.54;
			var cmToFeet = Math.floor(inches/12);
			var cmToInch = Math.ceil(inches % 12);

			if(ImpDd.find("option[data-feet='"+cmToFeet+"'][data-inch='"+cmToInch+"']").length < 1) {
				cmToInch = Math.floor(inches % 12);
			}

			ImpDd.find("option[data-feet='"+cmToFeet+"'][data-inch='"+cmToInch+"']").prop('selected', true);
		}
		
		metricDd.selectpicker('hide');
		metricDd.prop("disabled", true);
		ImpDd.find("option[data-value='"+metToImp+"']").prop('selected', true);
		ImpDd.selectpicker('show');
		ImpDd.prop("disabled", false);
		currUnit.val('Imperial');
		elem.text('Show Metric');
		ImpDd.selectpicker('refresh');
	}	
}
function updateState(contryDd){
	contryDd = $('#addressModal select[name="country"]');
	var country_code = contryDd.val();

	if(country_code == "" || country_code == undefined)
	{
		return false;
	}

	$.ajax({
		url: public_url+'countries/'+country_code,
		method: "get",
		data: {},
		success: function(data) {
			var selectedStates = contryDd.closest('.modal-body').find('select[name="addrState"]');
			var defaultState = selectedStates.data('selected');

			selectedStates.html("");
			$.each(data, function(val, text){
				var option = '<option value="' + val + '"';
				if(defaultState != '' && defaultState != null && val == defaultState)
					option += ' selected';
				option += '>' + text + '</option>';
				selectedStates.append(option);
			});

			contryDd.selectpicker('refresh');
			selectedStates.selectpicker('refresh');
		}
	});
}
function closeContactNoteSubview(e){
	if(e) e.preventDefault();
	$('#contact_note').hide("slide", {direction:"right"}, 500);
}
var maleAreas = {
	'ankle-n-foot': [ "791,2540,818,2537,841,2531,860,2523,876,2514,881,2534,886,2556,895,2581,899,2595,900,2616,898,2633,900,2657,901,2693,903,2707,907,2722,907,2746,901,2766,890,2771,860,2772,841,2772,809,2769,780,2767,774,2755,767,2738,770,2723,781,2709,801,2666,804,2646,797,2622,791,2539", "945,2530,960,2539,992,2550,1009,2555,1032,2553,1029,2581,1030,2641,1026,2656,1026,2671,1043,2703,1063,2724,1068,2739,1066,2748,1058,2763,1044,2764,958,2769,942,2761,932,2750,927,2742,928,2710,929,2663,929,2604,940,2572",
"1958,2479,1974,2491,1984,2503,1991,2520,1997,2544,2011,2579,2028,2607,2038,2612,2051,2590,2052,2556,2056,2511,2062,2470,2072,2436,2069,2475,2066,2510,2066,2554,2074,2608,2084,2639,2084,2667,2084,2699,2086,2739,2084,2763,2075,2780,2016,2781,1998,2771,1945,2753,1940,2737,1950,2719,1970,2709,1988,2693,1982,2673,1982,2654,1980,2588", "2139,2440,2149,2461,2154,2488,2158,2519,2161,2567,2174,2610,2188,2603,2194,2587,2206,2550,2225,2491,2249,2454,2238,2504,2227,2549,2220,2591,2218,2657,2214,2673,2212,2689,2224,2709,2241,2724,2242,2743,2227,2757,2202,2757,2177,2765,2150,2766,2132,2763,2116,2749,2102,2736,2100,2716,2112,2697,2115,2649,2115,2627,2124,2596,2133,2561,2140,2507" ],
	'elbows-n-arms' : [ "551,1415,587,1416,603,1421,611,1378,626,1330,642,1281,662,1220,673,1161,679,1085,680,1077,687,1047,697,1015,681,973,669,912,682,815,666,772,640,790,611,821,583,871,571,937,558,1021,538,1085,530,1130,527,1179,528,1259,521,1377,513,1417", "1200,1432,1239,1433,1276,1437,1277,1409,1272,1323,1282,1175,1276,1109,1265,1069,1251,949,1231,869,1202,822,1163,789,1148,830,1143,890,1145,965,1144,976,1123,1027,1137,1112,1137,1190,1143,1233,1168,1332,1182,1387,1189,1436", "1751,1461,1793,1465,1815,1458,1819,1449,1827,1391,1859,1272,1873,1193,1873,1114,1893,1007,1895,961,1881,901,1865,851,1869,815,1845,804,1832,803,1816,811,1795,843,1765,880,1761,926,1752,1004,1744,1075,1728,1140,1726,1198,1730,1263,1736,1344,1731,1427,1729,1451", "2422,1444,2452,1447,2477,1442,2497,1434,2501,1426,2496,1393,2489,1347,2487,1291,2489,1204,2488,1146,2479,1093,2456,1014,2446,947,2435,898,2435,863,2402,814,2376,792,2351,788,2320,799,2328,849,2319,867,2309,900,2301,941,2301,977,2320,1028,2337,1108,2338,1150,2346,1208,2356,1247,2410,1438" ],
	'hips-n-lower-back' : [ "657,1660,659,1638,664,1614,674,1586,683,1554,696,1527,708,1500,717,1480,722,1459,730,1432,733,1416,742,1403,738,1381,737,1367,732,1347,735,1317,724,1297,716,1286,703,1269,700,1259,693,1280,680,1334,673,1387,662,1447,653,1524,652,1613", "1145,1646,1148,1590,1143,1529,1135,1481,1127,1424,1122,1386,1117,1342,1114,1299,1109,1283,1076,1327,1067,1379,1064,1432,1078,1488", "1912,1922,1912,1893,1908,1858,1899,1829,1889,1774,1881,1729,1875,1656,1880,1586,1890,1549,1901,1587,1909,1638,1911,1659,1914,1687,1920,1665,1930,1637,1943,1619,1958,1615,2014,1611,2056,1604,2089,1585,2113,1556,2127,1551,2147,1570,2183,1586,2225,1594,2289,1603,2305,1629,2312,1664,2314,1677,2319,1672,2327,1639,2330,1616,2332,1589,2336,1569,2340,1536,2344,1563,2344,1583,2347,1613,2347,1648,2338,1727,2326,1798,2314,1845,2310,1866,2323,1833,2333,1785,2347,1724,2355,1664,2360,1620,2360,1541,2357,1504,2351,1453,2342,1404,2333,1345,2324,1295,2311,1260,2306,1235,2294,1236,2274,1247,2249,1266,2217,1290,2182,1317,2134,1337,2103,1346,2087,1339,2062,1330,2022,1313,1989,1290,1951,1271,1923,1259,1900,1254,1899,1290,1891,1339,1888,1387,1880,1436,1867,1523,1861,1610,1868,1725,1893,1849" ],
	'head' : [ "910,569,933,567,952,555,971,538,994,518,1011,493,1017,440,1025,447,1038,431,1047,411,1054,378,1048,362,1039,360,1031,367,1033,315,1028,286,1004,247,965,219,920,208,889,210,870,219,859,223,828,246,805,287,801,303,802,338,805,370,797,362,787,362,784,378,788,398,794,420,805,446,817,447,822,491,841,524,867,551,892,569", "2001,490,2022,448,2033,424,2050,412,2078,411,2104,412,2136,425,2154,448,2169,476,2174,494,2177,501,2187,488,2188,472,2192,446,2204,445,2213,424,2223,393,2226,370,2219,359,2208,361,2205,366,2205,337,2206,299,2201,276,2183,246,2163,230,2139,216,2113,206,2076,205,2042,215,2008,239,1996,251,1979,286,1975,308,1978,362,1976,367,1971,358,1961,359,1955,368,1956,397,1974,441,1984,445,1993,441" ],
	'knee-n-legs' : [ "809,2543,838,2533,867,2521,881,2512,878,2495,876,2476,875,2436,877,2405,879,2378,880,2359,884,2294,887,2253,884,2209,874,2162,870,2127,867,2114,866,2098,873,2071,879,2048,883,2008,888,1962,889,1926,886,1876,884,1827,891,1775,895,1740,898,1705,898,1660,896,1615,894,1596,887,1571,882,1546,873,1538,856,1514,842,1491,821,1446,791,1387,771,1346,751,1322,740,1308,722,1295,729,1412,715,1460,689,1516,660,1607,651,1642,670,1755,692,1851,710,1909,724,2035,721,2135,716,2231,724,2280,748,2382,793,2541", "958,2534,981,2548,1005,2555,1019,2557,1036,2556,1040,2535,1048,2480,1063,2417,1087,2331,1097,2240,1091,2150,1086,2111,1085,2062,1086,2024,1089,1989,1092,1961,1095,1944,1097,1926,1110,1877,1122,1820,1138,1737,1145,1669,1148,1638,1142,1618,1137,1589,1120,1554,1101,1510,1082,1459,1072,1408,1076,1369,1077,1331,1070,1327,1057,1335,1037,1358,1000,1414,971,1463,936,1516,905,1554,903,1602,900,1629,899,1654,900,1705,910,1777,921,1933,919,1957,918,1984,933,2089,946,2125,934,2186,927,2288,946,2467,946,2530", "2030,2641,2040,2642,2055,2629,2058,2612,2059,2580,2062,2551,2066,2526,2071,2498,2070,2476,2074,2448,2078,2413,2080,2381,2085,2354,2089,2309,2087,2271,2085,2237,2078,2210,2075,2175,2070,2142,2074,2118,2080,2099,2084,2074,2087,2051,2092,2017,2095,1983,2094,1940,2093,1868,2100,1798,2109,1737,2112,1682,2111,1648,2109,1610,2107,1579,2105,1570,2096,1578,2081,1590,2047,1602,2015,1609,1975,1611,1945,1615,1925,1642,1917,1669,1914,1675,1914,1658,1913,1636,1909,1621,1907,1594,1902,1577,1898,1561,1894,1544,1886,1546,1881,1568,1878,1602,1874,1633,1873,1677,1874,1728,1879,1774,1899,1873,1916,1929,1924,2008,1929,2113,1921,2204,1917,2259,1916,2305,1929,2380,2010,2596,2017,2619", "2164,2637,2184,2623,2196,2604,2202,2566,2215,2529,2230,2500,2244,2469,2253,2451,2271,2403,2284,2358,2295,2306,2302,2252,2298,2183,2292,2105,2292,2042,2296,1978,2307,1902,2316,1853,2338,1766,2350,1699,2357,1637,2357,1603,2353,1578,2350,1557,2341,1526,2335,1541,2323,1599,2315,1662,2311,1636,2304,1615,2292,1598,2268,1590,2246,1588,2217,1585,2197,1581,2175,1579,2142,1562,2130,1551,2119,1575,2115,1618,2112,1661,2117,1741,2124,1799,2126,1875,2125,1935,2124,1980,2130,2020,2142,2096,2148,2129,2140,2184,2132,2221,2128,2281,2130,2355,2142,2465,2144,2503,2152,2608,2153,2630" ],
	'mid-upper-back' : [ "1902,1275,1928,1282,1950,1290,1979,1305,1984,1286,1995,1254,2004,1221,2017,1184,2029,1148,2044,1124,2073,1097,2125,1096,2150,1111,2186,1146,2201,1182,2207,1209,2217,1240,2221,1269,2236,1286,2253,1289,2272,1270,2293,1255,2301,1240,2307,1237,2306,1212,2302,1188,2295,1156,2292,1122,2297,1083,2308,1036,2318,1018,2315,998,2310,971,2308,939,2312,910,2325,873,2335,823,2325,801,2356,791,2380,794,2372,771,2355,754,2339,745,2319,733,2303,725,2245,704,2233,696,2217,688,2160,683,2047,691,1957,695,1948,704,1942,711,1922,726,1878,731,1836,757,1813,807,1826,806,1865,812,1863,839,1873,896,1885,916,1890,1007,1886,1034,1894,1067,1906,1135,1896,1215" ],
	'neck' : [ "685,633,703,637,723,643,751,649,786,650,816,654,848,653,874,661,891,666,902,681,915,687,941,673,953,666,970,661,1032,661,1074,663,1117,659,1150,654,1165,650,1143,637,1105,623,1065,594,1017,560,1009,518,1010,497,1000,504,985,520,970,535,951,548,939,560,922,560,899,562,869,546,857,533,839,517,834,517,834,543,816,567,778,589,743,612", "1839,660,1857,671,1879,684,1916,696,1962,704,1960,707,1984,716,2020,716,2049,721,2085,725,2124,725,2156,718,2191,716,2203,713,2228,711,2252,699,2300,688,2337,664,2350,647,2326,636,2281,621,2240,596,2195,569,2176,547,2177,516,2176,502,2181,491,2177,469,2174,451,2168,426,2159,421,2143,414,2126,409,2112,407,2097,404,2080,407,2059,410,2039,416,2017,435,2006,466,1996,487,1996,510,2001,552,1996,564,1974,584,1934,612,1900,634" ],
	'shoulders' : [ "574,925,578,899,580,866,576,839,574,802,572,781,573,754,580,731,591,705,610,680,628,661,650,647,698,628,705,625,746,634,819,643,878,643,896,644,907,651,897,664,870,666,852,661,834,657,801,656,785,664,728,690,683,734,662,774,638,799,604,840", "946,668,978,661,1021,661,1054,681,1087,697,1126,717,1146,745,1156,778,1171,805,1199,829,1222,863,1238,918,1250,951,1247,899,1248,875,1258,820,1258,778,1248,734,1221,689,1185,660,1156,642,1139,639,1049,649,1021,647,993,645,959,647,941,652,941,660", "1760,893,1754,862,1748,825,1750,789,1760,738,1785,698,1819,667,1857,650,1865,651,1883,671,1930,688,1953,697,1963,716,1949,724,1925,732,1901,735,1865,740,1831,772", "2224,708,2232,688,2257,683,2301,664,2329,638,2341,640,2372,657,2403,684,2425,718,2439,759,2443,812,2439,845,2436,877,2426,871,2416,852,2398,831,2385,808,2357,771,2323,745,2286,729,2265,721" ],
	'wrist-n-hand' : [ "409,1564,431,1545,451,1526,462,1495,477,1467,502,1444,511,1424,512,1405,523,1412,546,1412,569,1410,589,1409,606,1410,605,1417,601,1445,599,1466,602,1479,606,1501,606,1538,606,1567,602,1585,599,1602,598,1616,596,1647,590,1672,584,1681,572,1680,568,1661,569,1633,572,1606,572,1591,568,1594,566,1610,562,1635,560,1670,556,1696,552,1710,542,1719,533,1713,528,1699,531,1682,533,1643,534,1605,534,1594,529,1611,525,1639,520,1665,518,1701,512,1723,501,1733,488,1726,488,1704,490,1663,493,1625,500,1597,499,1589,492,1606,486,1639,480,1660,478,1685,472,1697,461,1704,449,1697,449,1671,452,1635,461,1595,463,1564,457,1570,441,1578,425,1582,414,1578,411,1571", "1189,1426,1209,1425,1231,1426,1255,1423,1276,1427,1281,1434,1281,1447,1282,1455,1301,1477,1321,1498,1335,1533,1356,1565,1380,1583,1377,1599,1363,1603,1339,1594,1325,1581,1324,1586,1323,1604,1326,1627,1331,1652,1333,1673,1333,1711,1326,1723,1312,1723,1305,1705,1301,1680,1295,1647,1291,1625,1287,1607,1285,1616,1288,1637,1291,1663,1292,1694,1293,1720,1294,1732,1287,1750,1279,1754,1269,1750,1263,1716,1260,1676,1256,1637,1252,1617,1249,1629,1252,1653,1252,1675,1253,1704,1253,1721,1246,1738,1235,1737,1223,1716,1222,1667,1217,1611,1213,1619,1214,1645,1216,1662,1214,1692,1204,1701,1194,1689,1189,1652,1185,1611,1183,1588,1185,1507,1192,1480", "1628,1575,1648,1559,1669,1543,1680,1511,1692,1491,1719,1470,1729,1451,1728,1436,1742,1444,1757,1451,1777,1452,1791,1454,1804,1450,1820,1443,1822,1452,1819,1479,1818,1487,1827,1512,1827,1553,1826,1584,1822,1620,1820,1647,1818,1680,1815,1691,1808,1698,1797,1695,1794,1679,1794,1656,1794,1611,1790,1614,1786,1658,1785,1709,1779,1731,1765,1735,1755,1725,1757,1660,1758,1614,1751,1650,1749,1682,1745,1728,1743,1742,1734,1753,1721,1750,1714,1739,1714,1718,1715,1664,1723,1615,1720,1612,1718,1623,1711,1661,1706,1696,1702,1714,1696,1723,1680,1722,1673,1692,1678,1647,1683,1607,1686,1575,1675,1586,1656,1596,1636,1596,1628,1590", "2419,1671,2414,1615,2408,1577,2405,1533,2405,1497,2414,1472,2414,1437,2423,1437,2443,1438,2477,1431,2503,1422,2505,1444,2521,1457,2540,1476,2555,1500,2563,1523,2578,1539,2601,1559,2599,1571,2582,1575,2556,1569,2549,1560,2547,1569,2552,1590,2559,1648,2563,1682,2560,1699,2551,1706,2532,1690,2529,1661,2519,1617,2515,1590,2511,1596,2519,1638,2522,1676,2525,1697,2525,1711,2522,1733,2507,1734,2499,1724,2495,1699,2490,1667,2483,1623,2477,1597,2476,1606,2479,1633,2481,1687,2481,1714,2471,1719,2458,1717,2455,1695,2448,1655,2443,1598,2440,1593,2442,1623,2442,1664,2440,1679,2428,1684" ]
},
femaleAreas = {
	'ankle-n-foot' : [ "897,2764,871,2765,838,2766,797,2760,785,2761,787,2745,792,2727,795,2714,802,2701,810,2685,818,2658,812,2631,811,2609,812,2586,811,2573,828,2567,860,2557,888,2545,892,2564,902,2602,905,2618,903,2633,905,2670,905,2687,906,2721,907,2745", "930,2773,918,2765,916,2752,910,2734,914,2722,919,2698,915,2679,919,2662,924,2639,920,2612,930,2582,933,2545,945,2550,986,2567,1016,2577,1013,2599,1014,2626,1011,2651,1008,2665,1021,2703,1031,2739,1035,2758,1028,2764,963,2768,947,2766", "2083,2752,2067,2767,2041,2768,2013,2764,2001,2763,1986,2762,1968,2760,1962,2747,1969,2740,1978,2731,1988,2708,1993,2688,1992,2674,1989,2651,1989,2628,1988,2592,1975,2519,1967,2484,1975,2491,1988,2512,1991,2530,2009,2575,2022,2598,2044,2592,2045,2574,2044,2552,2045,2524,2054,2492,2061,2450,2069,2415,2071,2406,2072,2446,2070,2488,2069,2523,2067,2565,2076,2598,2084,2634,2082,2661,2087,2690,2090,2738", "2095,2753,2103,2761,2125,2770,2157,2773,2185,2767,2202,2766,2214,2761,2218,2748,2214,2739,2214,2732,2208,2726,2200,2707,2194,2692,2193,2669,2197,2650,2195,2623,2196,2590,2205,2548,2212,2506,2209,2498,2195,2524,2170,2582,2165,2607,2156,2611,2143,2602,2141,2592,2142,2560,2137,2516,2132,2469,2119,2430,2114,2419,2109,2424,2111,2452,2115,2502,2116,2559,2105,2614,2098,2638,2101,2661,2094,2724,2092,2739" ],
	'elbows-n-arms' : [ "631,1398,609,1396,579,1393,566,1393,559,1396,562,1381,563,1359,563,1312,561,1246,561,1176,564,1140,571,1103,580,1049,587,1016,596,948,609,873,620,818,643,781,677,736,698,696,712,748,702,801,692,828,691,877,703,906,708,922,687,1013,678,1089,676,1162,651,1291", "1178,1398,1217,1395,1236,1402,1234,1315,1229,1210,1219,1145,1203,1087,1197,1059,1191,995,1186,933,1178,868,1167,822,1150,791,1130,756,1092,714,1082,736,1082,772,1086,791,1096,812,1103,845,1101,870,1095,889,1097,908,1088,951,1088,966,1096,1013,1095,1071,1100,1117,1112,1188,1151,1302", "1823,1406,1791,1406,1763,1407,1765,1375,1765,1333,1768,1257,1775,1186,1786,1132,1795,1093,1807,1041,1812,963,1820,897,1825,837,1824,821,1843,805,1859,781,1863,745,1870,720,1881,707,1887,731,1896,761,1916,802,1928,837,1925,874,1926,907,1907,1010,1907,1059,1903,1109,1895,1164,1852,1307,1838,1349", "2378,1407,2410,1413,2450,1402,2444,1360,2445,1281,2443,1145,2431,1075,2420,1034,2401,905,2393,841,2374,809,2356,779,2349,768,2344,743,2328,704,2323,715,2322,738,2309,766,2287,823,2288,871,2301,926,2319,1023,2324,1086,2325,1143,2336,1220" ],
	'head' : [ "919,537,891,538,870,532,851,520,823,492,810,468,804,451,801,435,794,438,782,424,770,392,768,372,771,353,785,353,790,364,790,354,789,337,790,299,798,267,815,236,846,217,888,208,932,213,969,231,992,260,1002,285,1006,322,1005,364,1016,357,1023,362,1019,388,1016,407,1006,434,995,440,993,453,984,473,972,497,950,521", "2187,481,2167,457,2146,447,2115,441,2078,445,2047,472,2036,498,2025,490,2012,458,2008,440,1996,429,1982,389,1980,359,1991,357,1998,366,1998,353,1997,324,1998,297,2012,255,2036,224,2076,209,2122,207,2170,223,2200,258,2213,297,2214,338,2215,358,2226,352,2232,360,2236,379,2234,397,2218,432,2207,436,2197,445,2185,456,2183,467" ],
	'hips-n-lower-back' : [ "674,1591,665,1563,664,1532,663,1483,670,1421,680,1367,688,1318,703,1264,707,1254,713,1264,722,1278,718,1287,719,1312,719,1349,721,1374,712,1407,703,1437,694,1460,687,1504", "1131,1707,1129,1692,1132,1669,1134,1643,1137,1620,1139,1592,1141,1553,1135,1509,1113,1447,1099,1392,1091,1369,1094,1348,1095,1279,1103,1265,1117,1246,1121,1241,1132,1271,1135,1305,1145,1348,1152,1378,1161,1436,1165,1506,1158,1581", "1874,1734,1864,1702,1860,1672,1850,1631,1842,1593,1838,1550,1835,1496,1839,1451,1847,1398,1856,1347,1867,1289,1880,1248,1889,1216,1899,1197,1920,1213,1957,1238,2066,1318,2104,1321,2117,1311,2232,1235,2257,1220,2289,1220,2296,1240,2305,1271,2317,1325,2324,1365,2332,1413,2340,1475,2339,1572,2328,1633,2313,1698,2309,1733,2300,1774,2296,1800,2289,1832,2285,1856,2278,1912,2268,1953,2262,1990,2256,2020,2252,2038,2246,2028,2249,2008,2252,1977,2250,1969,2254,1952,2261,1913,2273,1853,2283,1792,2291,1740,2305,1665,2313,1560,2308,1528,2300,1553,2291,1583,2275,1627,2270,1654,2265,1677,2261,1670,2260,1641,2252,1613,2237,1589,2193,1582,2139,1575,2108,1549,2090,1524,2084,1517,2073,1526,2058,1542,2039,1559,2011,1570,1990,1573,1956,1575,1931,1580,1918,1592,1908,1626,1907,1649,1906,1664,1901,1666,1900,1650,1896,1628,1885,1591,1873,1548,1859,1521,1858,1504,1857,1538,1857,1577" ],
	'knee-n-legs' : [ "813,2583,807,2557,789,2464,772,2396,763,2346,754,2264,747,2162,749,2122,755,2083,755,2053,748,2004,730,1918,692,1694,664,1552,662,1535,669,1521,682,1484,689,1458,710,1402,718,1356,717,1294,727,1278,752,1290,763,1298,778,1323,814,1388,852,1453,887,1503,920,1510,904,1527,907,1563,896,1724,894,1749,896,1809,900,1870,901,1949,900,2030,891,2093,896,2150,907,2208,910,2252,903,2327,897,2407,892,2461,890,2522,889,2554,839,2570,823,2576", "936,2552,968,2568,1014,2583,1017,2559,1027,2517,1036,2466,1059,2382,1071,2307,1083,2197,1087,2137,1083,2098,1080,2079,1083,2023,1090,1979,1126,1759,1150,1625,1154,1605,1152,1568,1146,1530,1139,1492,1127,1454,1111,1408,1102,1374,1101,1354,1105,1318,1111,1287,1106,1270,1097,1264,1065,1279,1054,1292,1042,1303,996,1383,947,1487,937,1508,934,1540,930,1580,932,1616,936,1658,939,1713,937,1822,932,1880,931,1977,931,2036,937,2067,940,2103,935,2143,926,2201,921,2249,927,2331,932,2401", "2052,2617,2023,2629,2017,2618,2009,2583,2001,2551,1982,2511,1970,2477,1958,2432,1941,2379,1930,2327,1917,2186,1917,2147,1921,2115,1923,2089,1924,2070,1919,2035,1909,1970,1890,1849,1869,1727,1855,1649,1856,1632,1855,1595,1852,1558,1853,1531,1855,1509,1861,1505,1879,1551,1890,1576,1904,1644,1906,1617,1904,1601,1911,1589,1919,1577,1935,1566,1990,1567,2036,1557,2068,1531,2072,1564,2073,1621,2069,1648,2064,1683,2065,1814,2072,1890,2072,2021,2067,2075,2064,2111,2066,2148,2075,2191,2082,2245,2074,2403,2067,2440,2057,2490,2051,2548", "2163,2628,2135,2616,2132,2607,2134,2565,2127,2503,2113,2446,2109,2425,2105,2387,2103,2359,2096,2288,2096,2251,2100,2225,2104,2197,2114,2132,2114,2096,2108,2060,2104,2034,2102,1992,2102,1955,2102,1906,2106,1849,2109,1785,2110,1713,2102,1643,2097,1597,2098,1557,2103,1540,2118,1548,2147,1569,2221,1576,2248,1589,2260,1614,2264,1645,2268,1626,2275,1603,2286,1568,2307,1518,2314,1535,2316,1585,2262,1955,2253,2034,2247,2096,2257,2144,2258,2191,2253,2274,2245,2361,2230,2433,2210,2497,2192,2535,2178,2566" ],
	'wrist-n-hand' : [ "501,1584,499,1570,500,1557,495,1546,501,1524,506,1497,521,1466,545,1433,557,1415,562,1386,613,1388,633,1391,628,1432,635,1488,637,1549,637,1621,631,1637,616,1627,613,1588,611,1554,610,1583,610,1637,604,1659,592,1658,583,1623,582,1603,582,1632,580,1665,572,1675,562,1670,554,1606,553,1566,543,1606,538,1639,531,1655,521,1654,515,1636,516,1591,516,1580", "1189,1664,1200,1659,1212,1655,1240,1640,1260,1606,1274,1539,1278,1527,1243,1440,1236,1395,1209,1389,1175,1392,1180,1436,1183,1501,1190,1522,1191,1539,1189,1569,1191,1588,1202,1598,1208,1599,1207,1620,1197,1635,1184,1646", "1765,1399,1822,1399,1821,1427,1819,1461,1819,1495,1807,1526,1809,1560,1808,1585,1793,1598,1786,1596,1787,1607,1795,1628,1791,1640,1805,1639,1817,1658,1810,1671,1796,1663,1787,1667,1774,1650,1753,1640,1732,1588,1723,1539,1757,1438", "2377,1401,2414,1408,2445,1397,2451,1413,2461,1439,2492,1479,2511,1544,2508,1563,2510,1586,2493,1586,2491,1646,2491,1646,2477,1662,2466,1652,2462,1612,2457,1574,2454,1585,2449,1635,2447,1668,2435,1683,2422,1672,2422,1620,2418,1649,2407,1669,2396,1664,2395,1609,2396,1566,2393,1593,2390,1627,2381,1646,2370,1641,2366,1551" ],
	'neck' : [ "664,605,698,608,736,624,794,643,846,641,872,653,891,670,908,670,920,657,933,647,956,639,1034,629,1088,608,1112,597,1083,596,1040,585,988,562,971,539,971,500,950,509,930,526,892,532,871,525,844,509,814,477,820,534,809,569,783,583,739,594,688,596", "2069,441,2136,432,2172,436,2192,477,2188,509,2187,549,2208,583,2247,597,2309,599,2348,607,2347,621,2329,636,2249,678,2238,679,2110,728,1970,679,1853,618,1870,604,1895,597,1928,598,1975,590,2017,571,2033,547,2032,515,2026,489,2043,461" ],
	'mid-upper-back' : [ "1961,1258,1920,1229,1892,1216,1905,1183,1919,1151,1923,1111,1919,1032,1914,974,1918,937,1920,852,1920,825,1889,746,1880,698,1892,694,1912,684,1941,678,1972,678,2235,670,2254,671,2260,681,2293,687,2327,694,2329,710,2323,744,2313,779,2305,806,2293,838,2293,858,2295,882,2300,914,2291,969,2265,1105,2261,1155,2273,1184,2295,1239,2281,1236,2272,1231,2248,1239,2223,1254,2206,1244,2188,1222,2176,1184,2145,1095,2102,1048,2091,1048,2077,1058,2064,1064,2047,1077,2035,1108,2000,1197,1987,1227" ],
	'shoulders' : [ "612,865,612,820,613,734,616,690,616,675,624,644,660,604,689,595,729,599,785,622,858,629,879,641,861,657,847,655,831,647,807,649,782,655,743,664,700,664,696,688,702,713,683,741,631,807", "915,651,922,631,947,625,990,624,1047,611,1099,592,1137,602,1175,650,1180,676,1181,783,1178,871,1175,891,1169,869,1163,835,1146,802,1125,773,1096,736,1092,718,1088,704,1090,667,1081,661,1014,653,991,646,956,648,933,656", "1824,843,1821,792,1820,739,1821,681,1827,651,1866,602,1912,639,1976,672,1962,684,1944,677,1927,686,1907,696,1886,701,1868,757,1860,787", "2231,676,2258,659,2312,632,2347,608,2378,633,2393,686,2397,841,2391,846,2361,802,2345,770,2329,718,2322,703,2250,685" ]
};
function loadBodyAreas(areas, modal){
	
	if(modal[0].id == 'goalModal'){
		maleAreas['chest'] = ["816,920,773,919,735,907,704,877,687,822,675,795,669,767,668,779,657,786,644,795,634,801,634,789,643,775,661,738,679,713,718,686,793,654,804,651,840,652,874,658,893,657,912,659,916,673,928,678,935,661,948,657,971,656,1033,654,1073,674,1121,703,1167,743,1187,799,1183,809,1168,799,1163,783,1157,781,1159,789,1154,800,1153,810,1146,833,1133,858,1123,880,1096,905,1062,918,1021,925,989,915,975,914,955,904,946,894,872,894"];
		maleAreas['core'] = ["903,1555,878,1550,851,1514,809,1436,769,1361,747,1336,713,1285,697,1260,702,1231,701,1205,709,1169,715,1108,703,1030,691,978,687,874,716,890,735,898,765,914,797,912,832,903,870,886,881,881,944,883,974,897,1014,913,1072,909,1095,899,1138,878,1137,903,1136,937,1128,990,1112,1030,1118,1051,1110,1102,1106,1143,1111,1192,1112,1280,1079,1317,1038,1375,1010,1402,972,1472,946,1510"];
		femaleAreas['chest'] = ["921,867,866,863,863,834,859,802,845,781,829,768,817,758,799,755,777,750,746,752,716,749,713,749,708,751,699,720,690,670,697,656,746,655,791,640,842,638,868,652,887,665,914,652,922,642,996,638,1056,660,1094,657,1094,715,1083,750,1081,759,1073,748,1038,752,1003,750,970,751,939,772,922,795,912,817,914,851"];
		femaleAreas['core'] = ["913,1513,885,1505,859,1476,819,1403,775,1324,751,1293,725,1263,716,1230,715,1219,731,1183,744,1151,734,1081,720,1003,702,920,701,905,724,928,759,952,783,945,794,929,823,910,842,891,865,861,881,857,904,859,917,861,938,880,982,920,1024,938,1052,942,1083,959,1089,975,1083,1023,1080,1069,1078,1109,1081,1141,1102,1194,1093,1248,1056,1294,1019,1358,989,1420,956,1485,941,1505"]
	}
	else 
		maleAreas['chest'] = maleAreas['core'] = femaleAreas['chest'] = femaleAreas['core'] = [];
	
	var b = $(modal).find('.body')[0], multiplier = $(b).width() / 3000, m = document.getElementById('Map'), t=public_url+'bodytool/'+(areas==maleAreas?'male':'female'), f=[], prePartSrc = '';

	b.src = t + '/injuries.gif';

	while (m.firstChild) {
		m.removeChild(m.firstChild);
	}
	for(var i in areas){
		var c=new Image();
		c.src=t+'/'+i+'.gif';
		f.push(c);
		
		for(var j=0;j<areas[i].length;j++){
			var nvs = [], arr = areas[i][j].split(',');
			for(var k=0;k<arr.length;k++)
				nvs.push(Math.round(parseInt(arr[k])*multiplier));
			var a = document.createElement("area");
			a.shape = "poly";
			a.coords = nvs.join(',');
			
			a.setAttribute('part',i);
			a.onmouseover = function(){
				b.src = t + '/' + this.getAttribute('part') + '.gif';
			};
			a.onmouseout = function(){
				if(prePartSrc)
					b.src = prePartSrc;
				else
					b.src = t + '/injuries.gif';
			};
			a.onclick = function(){
				var part = this.getAttribute('part');
				/*$('.injuryList').addClass('hidden');
				$('.'+part).removeClass('hidden');*/
				showInjuryList(part);
				b.src = t + '/' + this.getAttribute('part') + '.gif';
				prePartSrc = b.src;
			};
			m.appendChild(a);
		}
	}
}
function showInjuryList(part){
	$('.injuryList').addClass('hidden');
	if(part)
		$('.'+part).removeClass('hidden');
}
function toggleBmTimeFields($element){
	var selectedVal = $(".ui-selected", $element).text().trim(),
		form = $element.closest('form'),
		bmTimeManualFields = $(form).find('.bm_time_manual');
	$element.find('input').val(selectedVal);

	if(selectedVal == 'Manual Time Entry')
	{	
		//bmTimeManualFields.show(); 
		bmTimeManualFields.removeClass('hidden');
	}	
	else
		bmTimeManualFields.addClass('hidden');
		//bmTimeManualFields.hide();
}
function ratingHandler(){
	$('.rating, .rating-tooltip').each(function() {
		($(this).val() > 0) ? ($(this).next(".label").show().text($(this).val() || ' ')) : $(this).next(".label").hide();
	});
	$('.rating-tooltip').rating({
		start: 0,
  		stop: 10,
		extendSymbol: function(rate) {
			$(this).tooltip({
				container: 'body',
				placement: 'bottom',
				title: 'Rate ' + rate
			});
		}
	});
	$('.rating, .rating-tooltip').on('change', function() {
		$(this).next('.label').show().text($(this).val());
	});
}
function medCondNotesModal(dd, clickedIndex){
	var modal = $('#medCondNotesModal');

	modal.find('input[name="entity"]').val(dd);
	modal.find('input[name="entityOptIdx"]').val(clickedIndex);
	modal.find('textarea').val('');
	modal.modal('show');
}
function fetchStepAndMarkComplete(stepNumb, completedOn, consultationDate){
	var step = $('ul#salesProcess a.todo-actions[data-step-number="'+stepNumb+'"]'),
		prevSteps = step.parent().prevAll();

	markStepComplete(step, completedOn);
	prevSteps.each(function(){
		var $this = $(this);

		if(!isStepComplete($this))
			markStepComplete($this);
	});
	$('input[name="salesProcessCompleted"]').val(stepNumb);
	if(typeof consultationDate != 'undefined' && consultationDate)
		$('input[name="consultationDate"]').val(consultationDate);
	if(stepNumb >= 9)
		$('#clientStatusGroupUnderSalesProcess').removeClass('hidden');
}
function downgradeSteps(oldSaleProcessStep, clientNewSaleProcessStep, consultationDate){
	var step = $('ul#salesProcess a.todo-actions[data-step-number="'+oldSaleProcessStep+'"]'),
		prevSteps = step.parent().prevAll();

	markStepIncomplete(step);
	prevSteps.each(function(){
		var $this = $(this);

		if($this.find('a').data('step-number') != clientNewSaleProcessStep)
			markStepIncomplete($this);
		else
			return false;
	});
	$('input[name="salesProcessCompleted"]').val(clientNewSaleProcessStep);
	if(typeof consultationDate != 'undefined')
		$('input[name="consultationDate"]').val(consultationDate);
	if(clientNewSaleProcessStep < 9)
		$('#clientStatusGroupUnderSalesProcess').addClass('hidden');
}
function markStepComplete(row, completedOn){
	row.find("i").removeClass("fa-square-o").addClass("fa-check-square-o");
	row.find("span.desc").css({
		opacity: 0.25,
		textDecoration: 'line-through'
	});	
	if(typeof completedOn != 'undefined' && completedOn)
		row.find("span.compl-date").text(completedOn);
}
function markStepIncomplete(row){
	row.find("i").removeClass("fa-check-square-o").addClass("fa-square-o");
	row.find("span.desc").css({
		opacity: 1,
		textDecoration: 'none'
	});	
	row.find("span.compl-date").text('');	
}
function isStepComplete(row){
	return row.find("i").hasClass('fa-check-square-o');
}

function isEpic(elem){
	epicDdValue = elem.val();
	if(epicDdValue =="onlinesocial" || epicDdValue=="mediapromotions" )
		$('.referencewhere').removeClass('hidden');
	else 
		$('.referencewhere').addClass('hidden');
}

/* start: Toggle renewal period fields based on installment plan */
/*function toggleRenwPeriod(instllPlan){
	if(typeof instllPlan == 'undefined')
		instllPlan = $('#editMembSub select[name="instllPlan"]').val();

	var renwDateGroup = $('#renwDateGroup'),
		renwDayGroup = $('#renwDayGroup');

	if(instllPlan == 'every_month'){
		//Monthly Renewal
		renwDateGroup.show();
		renwDayGroup.hide();
	}
	else if(instllPlan == 'every_week'){
		//Weekly Renewal
		renwDateGroup.hide();
		renwDayGroup.show();
	}
	else if(!instllPlan || instllPlan == 'single_payment'){
		//No Renewal
		renwDateGroup.hide();
		renwDayGroup.hide();
	}
}*/
/* end: Toggle renewal period fields based on installment plan */

/* start: Toggle discount fields based on input */
function toggleMembdiscFields(cb){
	if(cb.is(':checked')) //Apply discount
		$('#discFields').show();
	else
		$('#discFields').hide();
}
/* end: Toggle discount fields based on input */

/* start: Calculate and set discount duration options */
/*function setMembdiscdurOpts(){
	var payPlanDd = $('#payPlan'),
		startDate = $('#membStartDate').val(),
		endDate = $('input[name="membEndDate"]').val(),
		discDurDd = $('#discDur'),
		discDur = discDurDd.val(),
		max = 0,
		options = '<option value="">-- Select --</option>';

	if(payPlanDd.val() && startDate && endDate){
		var payPlanOpt = payPlanDd.find('option:selected'),
			unit = payPlanOpt.data('unit'),
			amount = payPlanOpt.data('amount');

		max = Math.ceil((moment(endDate).diff(moment(startDate, 'ddd, D MMM YYYY'), unit))/amount);
	}

	for(var i=1; i<=max; i++){
		options+='<option value="'+i+'">'+i+'</option>';
	}
	discDurDd.html(options)

	if(discDur && !discDurDd.find('option[value="'+discDur+'"]').length)
		discDur = '';
	
	discDurDd.val(discDur).selectpicker('refresh');
}*/
/* start: Calculate and set discount duration options */

/* start: Calculate and set membership end date */
function setMembEndDate(callback){
	var membershipDd = $('#membership'),
		startDate = $('#membStartDate').val(),
		endDate = '';
	
	if(membershipDd.val() && startDate){
		var membershipOpt = membershipDd.find('option:selected'),
			membLength = membershipOpt.data('length'),
			membLengthUnit = membershipOpt.data('length-unit');

		endDate = moment(startDate, 'ddd, D MMM YYYY').add(membLength, membLengthUnit).format("YYYY-MM-DD");
	}
	$('input[name="membEndDate"]').val(endDate);

	if(typeof callback != 'undefined')
		callback();
}
/* end: Calculate and set membership end date */

/* start: Calculate and set payment plan options */
/*function setMembPayPlanOpts(callback){
	var startDate = $('#membStartDate').val(),
		endDate = $('input[name="membEndDate"]').val(),
		payPlanDd = $('#payPlan'),
		payPlanOpts = payPlanDd.find('option[value!=""]');

	payPlanOpts.addClass('hidden');	
	if(startDate && endDate){
		var membershipOpt = $('#membership option:selected');
		if(hasData(membershipOpt, 'max-pay-plan')){
			var maxPayPlan = membershipOpt.data('max-pay-plan');
			payPlanOpts.filter('[value="'+maxPayPlan+'"]').prevAll().andSelf().removeClass('hidden');
		}
		else{
			var endDateMom = moment(endDate),
				startDateMom = moment(startDate, 'ddd, D MMM YYYY');
			payPlanOpts.each(function(){
				var option = $(this),
					unit = option.data('unit'),
					amount = option.data('amount');

				var diff = endDateMom.diff(startDateMom, unit);
				if(diff >= amount){
					option.removeClass('hidden');
					membershipOpt.data('max-pay-plan', option.val())
				}
			})
		}
	}
	var payPlan = payPlanDd.val();
	if(payPlan && payPlanOpts.filter('[value="'+payPlan+'"]').hasClass("hidden"))
		payPlanDd.val('')
	payPlanDd.selectpicker('refresh')
	payPlanDd.prev().find('a.hidden').parent().addClass('hidden')

	if(typeof callback != 'undefined')
		callback();
}*/
/* end: Calculate and set payment plan options */

function consultDateExpiredMsg(){
	swal({
        title: "Consultation date has expired.",
        type: 'warning',
        allowEscapeKey: false,
        confirmButtonText: 'Okay',
        confirmButtonColor: '#ff4401'
    });
	return;
}

/* start: Client-Membership modal submit */
function submitClientMemb(){
	var form = $('#editMembSub form'),
		formData = {},
		membStartDateField = form.find('#membStartDate');
	formData['clientId'] = form.find('input[name="clientId"]').val();
	formData['clientMembId'] = form.find('input[name="clientMembId"]').val();
	formData['membership'] = form.find('#membership').val();
	formData['payPlan'] = form.find('#payPlan').val();
	formData['membStartDate'] = dateStringToDbDate(membStartDateField.val());
	formData['membEndDate'] = form.find('input[name="membEndDate"]').val();
	if(form.find('#applyDisc').is(':checked')){
		formData['discAmt'] = form.find('#discAmt').val();
		formData['discDur'] = form.find('#discDur').val();
	}
	if(formData['clientMembId'])
	//if(form.data('mode') == 'edit')
		formData['updateOpt'] = $('#updateClientMemb input[name="updateOpt"]:checked').val();
	/*if(formData['instllPlan'] == 'every_month')
		formData['renwPeriod'] = form.find('select[name="renwDate"]').val();
	else if(formData['instllPlan'] == 'every_week')
		formData['renwPeriod'] = form.find('select[name="renwDay"]').val();
	else if(formData['instllPlan'] == 'single_payment')
		formData['renwPeriod'] = 0;*/

	$.post(form.attr('action'), formData, function(data){
		var data = JSON.parse(data);
		if(data.status == "updated"){
			location.reload(true);
		}  
	});
}
/* end: Client-Membership modal submit */

/* start: Detect if membership is set to change */
/*function isMembChanged(){
	var membChanged = true;
	if(!isFieldUpdated($('#membership')) && !isFieldUpdated($('#payPlan'))){
		var applyDiscCb = $('#applyDisc');
		if(!isFieldUpdated(applyDiscCb)){
			if(applyDiscCb.is(':checked')){
				if(!isFieldUpdated($('#discAmt')) && !isFieldUpdated($('#discDur'))){
					membChanged = false;
				}
			}
			else
				membChanged = false;
		}
	}
	return membChanged;
}*/
/* end: Detect if membership is set to change */

/* end: Detect if field value has been changed */
/*function isFieldUpdated(field){
	var tag = field.prop('tagName').toLowerCase(),
		type = field.prop('type');

	if(tag == 'select' || (tag == 'input' && type == 'text')){
		if(field.val() == field.data('val'))
			return false;
	}
	else if(tag == 'input' && type == 'checkbox'){
		if(field.prop('checked') == field.data('checked'))
			return false;
	}
	return true;
}*/
/* end: Detect if field value has been changed */

jQuery(document).ready(function(){
	ratingHandler();
	$('input[name="bm_time_day"]').datepicker({autoclose:true, dateFormat:"D, d M yy",maxDate: new Date()});

	$(".bm_time_selectable").selectable({
		stop: function() {
			toggleBmTimeFields($(this));
		}
	
	});

	initCustomValidator();

	toggleBmTimeFields($('.bm_time_selectable'));

	currentClientId = $('#currentClientId').val();

	$(document).on("change", "select.customValDdField", function(){
		if($(this).val() != null && $(this).val() != '')
			setFieldNeutral($(this));
	});

    initTelInput($(".cntryCode"));

    $(document).on("countrychange", ".cntryCode", function(){
    	initTelInputOnChange($(this));
	}); 
	
    $('select[name="referrer"]').change(function(){
    	isEpic($(this));
	});

	isEpic($('select[name="referrer"]'));

	$('select[name="allergies"]').change(function(){
		toggleNotes(this);
	});
	toggleNotes($('select[name="allergies"]'));


	$('select[name="chronicMedication"]').change(function(){
		toggleNotes(this);
	});
	toggleNotes($('select[name="chronicMedication"]'));


	$('select[name="smoking"]').change(function(){
		toggleSmokeDays();
	});
	toggleSmokeDays();


	// $('#heightUnit').click(function(){
	// 	toggleMeasureDd('height', $(this));
	// });
	// toggleMeasureDd('height', $('#heightUnit'), true);


	// $('#weightUnit').click(function(){
	// 	toggleMeasureDd('weight', $(this));
	// });
	// toggleMeasureDd('weight', $('#weightUnit'), true);


	$('input[name="referralNetwork"]').change(function(){
		toggleReference($(this).val());
	});
	toggleReference();

	$('select.medCond').on('changed.bs.select', function(e, clickedIndex, newValue){
		var $this = $(this);

		if(clickedIndex == 0){
			$this.find('option:not([value="None"])').prop('selected', false);
			$this.closest('.vp-item').find('input[type="hidden"]').val('');
			$this.parent().removeClass('open');
		}
		else{
			if(newValue)
				medCondNotesModal($this.attr('name'), clickedIndex);
			else{
				var allNotesField = $this.closest('.vp-item').find('input[type="hidden"]'),
					allNotes = allNotesField.val(),
					opt = $this.find('option').eq(clickedIndex).attr('value');

				if(allNotes != ''){
					var formData = JSON.parse(allNotes);

					if(opt in formData){
						$(formData).removeProp(opt);
						allNotesField.val(JSON.stringify(formData));
					}
				}
			}
			
			$this.find('option[value="None"]').prop('selected', false);
		}
		
		$this.selectpicker('refresh');
	});
	$("#medCondNotesModal .submit").click(function(){
		var modal = $(this).closest('div.modal'),
			notes = modal.find('textarea').val();
		if(notes){
			var entity = modal.find('input[name="entity"]').val(),
				dd = $('select[name="'+entity+'"]'),
				allNotesField = dd.closest('.styled-select').find('input[type="hidden"]'),
				allNotes = allNotesField.val(),
				entityOptIdx = modal.find('input[name="entityOptIdx"]').val(),
				opt = dd.find('option').eq(entityOptIdx).attr('value');
			if(allNotes == '')
				var formData = {};
			else
				var formData = JSON.parse(allNotes);

			formData[opt] = notes;
			if(entity == 'medicalCondition'){
				$(".med_notes").empty();	
				$.each(formData,function(key, Obj){	
					var html = '<div class="form-group " data-med="'+key+'">\
					<label class="strong medinotes">'+key+' </label>\
					<input class="form-control" value="'+Obj+'" name="medicaNotes">\
					</div>';
					$('.med_notes').append(html);
				});
			}else if(entity == 'relMedicalCondition'){
				$('.rel_med_notes').empty();
				$.each(formData,function(key, Obj){	
					console.log(key, Obj);
					var html = '<div class="form-group " data-med="'+key+'">\
					<label class="strong relmedinotes">'+key+' </label>\
					<input class="form-control" value="'+Obj+'" name="relmedicaNotes">\
					</div>';
					$('.rel_med_notes').append(html);
				});
			}
			allNotesField.val(JSON.stringify(formData))
		}
	});

	/****** Medical Notes Add Box *******/
	$('select[name="medicalCondition"]').change(function(){
		var allVal = $(this).val();
		$('.med_notes .form-group').each(function(){
			if($.inArray($(this).data('med'),allVal) < 0){
				$(this).remove();
			}
		})
	});

	$('select[name="relMedicalCondition"]').change(function(){
		var allVal = $(this).val();
		$('.rel_med_notes .form-group').each(function(){
			if($.inArray($(this).data('med'),allVal) < 0){
				$(this).remove();
			}
		})
	});

	$('input[name="medicaNotes"]').change(function(){
			$this = $(this);
			dd = $('select[name="medicalCondition"]'),
			allNotesField = dd.closest('.vp-item').find('input[type="hidden"]'),
			allNotes = allNotesField.val();
			if(allNotes != undefined && allNotes != ''){
				var formData ={};
				data = JSON.parse(allNotes);
				$.each(data,function(key,obj){
					if(key == $this.closest('.form-group').data('med')){
						formData[key] = $this.val();
					}else{
						formData[key] = obj;
					}
				});
			}
			allNotesField.val(JSON.stringify(formData))
	})
	$('input[name="relmedicaNotes"]').change(function(){
		$this = $(this);
		dd = $('select[name="relMedicalCondition"]'),
		allNotesField = dd.closest('.vp-item').find('input[type="hidden"]'),
		allNotes = allNotesField.val();
		if(allNotes != undefined && allNotes != ''){
			var formData ={};
			data = JSON.parse(allNotes);
			$.each(data,function(key,obj){
				if(key == $this.closest('.form-group').data('med')){
					formData[key] = $this.val();
				}else{
					formData[key] = obj;
				}
			});
		}
		allNotesField.val(JSON.stringify(formData))
	})
	/****** //Medical Notes Add Box *******/






	// var preferredDaysField = $('#step-2 input[name="preferredTraingDays"]');
	// 	preferredDays = preferredDaysField.val();
	// if(preferredDays){
	// 	console.log(preferredDays);
	// 	preferredDays = JSON.parse(preferredDays);
	// 	if(Object.keys(preferredDays).length){
	// 		var cbxs = preferredDaysField.closest('.form-group').find('input[type="checkbox"]');
	// 		$.each(preferredDays, function(day, times){
	// 			var cb = cbxs.filter('[data-day="'+day+'"]');
	// 			$.each(times, function(key, time){
	// 				cb.filter('[value="'+time+'"]').prop('checked', true)
	// 			});
	// 		});
	// 	}
	// }
    //
	// $("#step-2 input.preferredTraingDays").change(function(){
	// 	var $this = $(this),
	// 		preferredDaysField = $this.closest('.form-group').children('input[name="preferredTraingDays"]'),
	// 		// preferredDaysField = $this.closest('.form-group').find('.preferredTraingDays'),
	// 		preferredDays = preferredDaysField.val(),
	// 		day = $this.data('day');
    //
	// 	console.log(preferredDays);
	// 	// console.log(preferredDaysField);
    //
	// 	if(preferredDays == '')
	// 		var formData = {};
	// 	else
	// 		var formData = JSON.parse(preferredDays);
    //
	// 	if($this.is(':checked')){
	// 		if(day in formData){
	// 			var preferredTime = formData[day];
    //
	// 			preferredTime.push($this.val());
	// 			formData[day] = preferredTime;
	// 		}
	// 		else
	// 			formData[day] = [$this.val()];
	// 	}
	// 	else{
	// 		var preferredTime = formData[day];
    //
	// 		preferredTime.splice(preferredTime.indexOf($this.val()), 1);
	// 		if(preferredTime.length){
	// 			formData[day] = preferredTime;
	// 		}
	// 		else
	// 			$(formData).removeProp(day);
	// 	}
    //
	// 	// $('input[name="preferredTraingDays"]').val(JSON.stringify(formData));
	// 	preferredDaysField.val(JSON.stringify(formData))
	// });












	$('#waiverModal button#mod_cancel').click(function(){
		window.location.reload();
	});


	$('#waiverModal button#submit').click(function(){
		var modal = $('#waiverModal');
		var date = modal.find('input[name="waiverDate"]');
		var dateVal = date.val();
		var terms = modal.find("input[name='client_waiver_term']");
		var isFormValid = true;

		var formGroup = date.closest('.form-group');
		var helpBlock = formGroup.find("span.help-block");
		if(dateVal == null || dateVal == ''){
			isFormValid = false;
			setFieldInvalid(formGroup, 'Please select a date.', helpBlock)
		}
		else
			setFieldValid(formGroup, helpBlock);

		var formGroup = terms.closest('.checkbox');
		var helpBlock = formGroup.find("span.help-block");
		if(terms.is(':checked'))
			setFieldValid(formGroup, helpBlock);
		else{
			isFormValid = false;
			setFieldInvalid(formGroup, 'Please accept the terms.', helpBlock)
		}	
		if(isFormValid){
			formData = {};
			formData['waiverDate'] = dateVal;
			formData['client_waiver_term'] = terms.val();
			formData['parqId'] = $('#form input[name="parqId"]').val();
			$.ajax({
				url: public_url+'waiver/save',
				method: "POST",
				data: formData,
				success: function(data) {
					if(data == 'true'){
						modal.modal('hide');
						window.location= 'PersonalDetails';
					}
				}
			});	
		}
	});
	
	$("#waiverDate").datepicker({autoclose:true, format:"D, d M yy"});
	$('#waiverModal').on('show.bs.modal', function(){
		var fName = $('#firstName').val(),
			lName = $('#lastName').val();
		$(this).find('input[name="waiver-client-name"]').val(fName+' '+lName);	
	});
	// $('#waiverModal').on('hide.bs.modal', function(){
	// 	var clientid = $('input[name="client_id"]').val();
	// 	window.location.href = public_url+'client/'+clientid;
	// });

	$('div#panel_assess_progress button#finish-parq-summary').click(function(){
		switchTab('#panel_overview')
		return false;
	});

	$('#addressModal button#addModalOk').click(function(){
		var modal = $(this).closest('.modal');
		console.log(modal);
		var invalid = false;
		modal.find('.modal-body').find(':input').each(function(){
			console.log($(this));
			if(!$(this).valid())
				invalid = true;
		});
		console.log(invalid);
		if(!invalid)
			modal.modal('hide');
	});
	$('#addressModal button#addModalCanc').click(function(){
		$('#addressModal input[name="addressline1"]').val('');
		$('#addressModal input[name="addressline2"]').val('');
		$('#addressModal input[name="city"]').val('');
		$('#addressModal select[name="country"]').val('');
		$('#addressModal select[name="addrState"]').val('');
		$('#addressModal input[name="postal_code"]').val('');
		$('#addressModal select[name="timezone"]').val('');
		$('#addressModal select[name="currency"]').val('');
	});
	$('#addressModal select[name="country"]').on('changed.bs.select', function (e) {
		updateState();
	});
	updateState();


	$('.closeModal').change(function(){
		var $this = $(this),
			modal = $this.closest("div.modal"),
			modalId = modal.attr('id'),
			chkboxs = modal.find('.injuryList').find('input[type="checkbox"]');

		if($this.is(':checked')){
			if(modalId == 'goalModal')
				chkboxs.prop('checked', true)
			else if(modalId == 'injuryModal')
				chkboxs.prop('checked', false)
			
			modal.modal('hide');	
		}
		else{
			if(modalId == 'goalModal')
				chkboxs.prop('checked', false)
			else if(modalId == 'injuryModal')
				chkboxs.prop('checked', true)
		} 
	});
	$(".bodyPartModal .injuryList input[type='checkbox']").change(function(){
		var $this = $(this),
			modal = $this.closest("div.modal"),
			modalId = modal.attr('id'),
			chkbox = modal.find('.closeModal');

		if(modalId == 'goalModal'){
			if(!$this.is(':checked')){
				if(chkbox.is(':checked'))
					chkbox.prop('checked', false)
			}
		}
		else if(modalId == 'injuryModal'){
			if($this.is(':checked')){
				if(chkbox.is(':checked'))
					chkbox.prop('checked', false)
			}
		}
	});
	$(".bodyPartModal").on('hide.bs.modal', function () {
		$(this).find('.injuryList').addClass('hidden')
    });
    if(!Main.isSmallDeviceFn()){
	    $("#injuryModal").on('show.bs.modal', function () {
	    	var m=$(this);
	    	
			setTimeout(function(){
				// alert('init');
				loadBodyAreas($('input[name="client-gender"]').find("input[name='gender']").val()=='Male'?maleAreas:femaleAreas, m);
			}, 200);
	    });
		$("#goalModal").on('show.bs.modal', function () {
			
			var n=$(this);
			setTimeout(function(){
				// alert('init');
				loadBodyAreas($('input[name="client-gender"]').find("input[name='gender']").val()=='Male'?maleAreas:femaleAreas, n);
			}, 200);



	    });
	}
	else{
		$('.bodyPartModal select.bodyPartsDd').change(function(){
			showInjuryList($(this).find('option:selected').data('part'));
		});
		$(".bodyPartModal").on('show.bs.modal', function(){
			showInjuryList($(this).find('select.bodyPartsDd option:selected').data('part'));
	    });
	}
	
	$.get(public_url+'clients/'+$('#form input[name="client_id"]').val()+'/co', function(data){
		$("#clientList").typeahead({ 
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="clientId"]').val(selection.id);
				$('input[name="isReferenceDeleted"]').val('');
			}
		});
	},'json');
	$.get(public_url+'staffs/all', function(data){
		$("#staffList").typeahead({ 
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="staffId"]').val(selection.id);
				$('input[name="isReferenceDeleted"]').val('');
			}
		});
	},'json');
	$.get(public_url+'contacts/all', function(data){
		$("#proList").typeahead({ 
			highlighter: function(item){
    			var data = item.split('|');
    			return data[0]+'<br><span>'+data[1];
    		},
			source:data, 
			items:'all', 
			afterSelect:function(selection){
				$('input[name="proId"]').val(selection.id);
				$('input[name="isReferenceDeleted"]').val('');
			}
		});
	},'json');
	
	
	$('.closeContactNoteSubview').on('click', closeContactNoteSubview);
	$eventDetail = $('.summernote');
	$('.summernote').summernote({
		oninit: function() {
			if ($eventDetail.code() == "" || $eventDetail.code().replace(/(<([^>]+)>)/ig, "") == "") {
				$eventDetail.code($eventDetail.attr("placeholder"));
			}
		},
		onfocus: function(e) {
			if ($eventDetail.code() == $eventDetail.attr("placeholder")) {
				$eventDetail.code("");
			}
		},
		onblur: function(e) {
			if ($eventDetail.code() == "" || $eventDetail.code().replace(/(<([^>]+)>)/ig, "") == "") {
				$eventDetail.code($eventDetail.attr("placeholder"));
			}
		},
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
		]
	});
	
	$('input[name="contactCbkDate"]').datepicker({autoclose:true, dateFormat:"D, d M yy"});
	$('ul#salesProcess a.todo-actions').click(function(e){//.conditionalSubview
		e.preventDefault();

		var $this = $(this);

		/*if(isStepComplete($this) || ($this.data('step-dependant') == true && !isStepComplete($this.parent().prev()))){
			e.stopImmediatePropagation();
			return false;
		}*/
		if(isStepComplete($this) || ($this.data('step-dependant') && !isStepComplete($('ul#salesProcess').find('a[data-step-number="'+$this.data('step-dependant')+'"]')))){
			e.stopImmediatePropagation();
			return false;
		}
		
		if($this.data('target-subview') == 'contact')
			$('#contact_note').show("slide", {direction:"right"}, 200);
		else if($this.data('step-number') == '10'){
			if(hasConsultDateExpired())
				return consultDateExpiredMsg();
			
			swal({
	            title: 'Have you emailed the price to this client?',
	            allowOutsideClick: true,
	            showCancelButton: true,
	            confirmButtonText: 'Yes',
	            confirmButtonColor: '#ff4401',
	            cancelButtonText: "No"
		    }, 
	        function(isConfirm){
	        	if(isConfirm){
	        		var formData = {};
	        		formData['stepNumb'] = $this.data('step-number');
	        		formData['clientId'] = $('#form input[name="client_id"]').val();

	        		$.post(public_url+'clients/sales-process/update', formData, function(data){
						var data = JSON.parse(data);
						if(data.status == "updated"){
							if('salesProcessDate' in data)
								var completedOn = data.salesProcessDate;
							else
								var completedOn = '';
							fetchStepAndMarkComplete($this.data('step-number'), completedOn)
						}
					});
	        	}
	        });
		}
	});
	$('#contactStatus').change(function(){
		var f=$(this).closest('form').find('.callback'), s=$('#contactCbkDate');
		if($(this).val()=='contacted'){
			f.hide();
			s.prop('required', false);
		}
		else{
			f.show();
			s.prop('required', true);
		}
	});
	$('#contact_note [type="submit"]').click(function(e){
		e.preventDefault();

		var form = $(this).closest('form'),
			isFormValid = form.valid();

		if(form.find('.gender').length){
			var isGenderValid = validateGender(form, 'gender');
			if(!isGenderValid)
				isFormValid = false;
		}
		if(isFormValid){
			var formData = {}, cn=$('#contact_note');
			formData['gender'] = $('input[name="gender"]:checked').val();
			formData['status'] = $('#contactStatus').val();
			if(formData['status'] != 'contacted')
				formData['cbkdate'] = moment(cn.find('input[name="contactCbkDate"]').val(), 'ddd, D MMM YYYY').format('YYYY-MM-DD');
			var notes = $('#contactNote').code();
			formData['note'] = (notes == 'Write note here...' || !notes)?'':notes;
			formData['clientId'] = currentClientId;
			$.ajax({
				url: public_url+'sales/contact-note/save',
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "added"){
						closeContactNoteSubview();
						if(formData['gender']){
							var p = $('.clientPreviewPics');
							p.attr('src', p[0].src.replace('noimage.gif',formData['gender'].toLowerCase()+'.gif'));
							cn.find('.gender').addClass('no-display');
						}

						if(formData['note']){
							var createdDatetime = dbDateToDateString(formData['createdDatetime']);

							$('#notesPanel .panel-body div:last-child').remove();
							$('#notesPanel .panel-body').prepend('<div class="col-md-12"><p>'+formData['note']+'</p><p><small>Created on: '+createdDatetime+'&nbsp;&nbsp; | &nbsp;&nbsp;Category: Contact </small></p><hr class="notes-hr"></div>');

							$('#contact-notes div:last-child').remove();
							$('#contact-notes').prepend('<div><p>'+formData['note']+'</p><p><small> Created on: '+createdDatetime+' </small></p><hr class="notes-hr"></div>');
						}

						if('changeStatus' in data){
							realTimeUpdate('accStatus', data.changeStatus);	
							var c = $('ul#salesProcess a.todo-actions[data-step-number="1"]');
							if('salesProcessDate' in data)
								var completedOn = data.salesProcessDate;
							else
								var completedOn = '';
							markStepComplete(c, completedOn);
						}
					}
				}
			});
		}
	});
	
	
	$(".checkbox input[value=All]").on('click', function(){
		$(this).closest('.form-group').find('input[type=checkbox]').attr('checked', this.checked);
	});
	$(".checkbox input[value=L_All], .checkbox input[value=R_All]").on('click', function(){
		var a=this.value.substr(0,2), c=this.checked;
		$(this).closest('.form-group').find('input[type=checkbox]').each(function(k,ele){
			if (ele.value.indexOf(a) == 0) {
				ele.checked = c;
			}
		})
	});

	$("#myModal #accordion").on('shown.bs.collapse', function(e){
		var input = $(e.target).find('input')
			value = input.val();

		input.focus().val('').val(value)
    });

	$("#myModal #accordion input").on('keydown', function(e){
		if(e.keyCode == 13){
			var thisAccord = $(this).closest('.collapse'),
				nextPanel = thisAccord.closest('.panel').next();

			if(nextPanel.length){
				thisAccord.collapse('hide');
				nextPanel.children('.collapse').collapse('show');
			}
		}
	});

	$("#showBenchmarkBox").click(function (e){
		$('.bm_time_manual').show();
 	});

	/* start: Open specific tab on page load */
	var hash = window.location.hash;
	if(hash){
		hashs = hash.split('#')
		hashs.splice(0,1);
		if(hashs.length>1){
			$.each(hashs, function(key, value){
				if(key == 0)
					switchTab('#'+value)
				else{
					var param = value.split('=');
					/*if(param[0] == 'accId'){
						$('#'+param[1]).trigger('click');
					}
					else*/ if(param[0] == 'step'){
						$('#wizard').smartWizard("goToStep", param[1]);
					}
				}
			})
		}
	}
	/* end: Open specific tab on page load */

    
	$('.callSubview').click(function(e){
		e.preventDefault();
		var $this = $(this),
			clientId = $('#clientId').val();

		if($this.hasClass('todo-actions'))
			salesProcessStepNumb = $this.data('step-number')
		else
			salesProcessStepNumb = 2;

		if(salesProcessStepNumb > 3){
			if(hasConsultDateExpired())
				return consultDateExpiredMsg();
			
			var extra = '&consultationRestriction=true&enableDatePeriod=15';
		}
		else
			var extra = '';

		openSubview($(this), 'clientId='+clientId+extra);
	});
	

	$('select[name="clientStatusUnderSalesProcess"]').change(function(){
		var $this = $(this),
			formData = {entityId:$('#currentClientId').val(), entityProperty:$this.data('realtime'), accStatus:$this.val()};

		$.post(public_url+'client/'+formData.entityId+'/update-field', formData, function(data){
			var data = JSON.parse(data);
			if(data.status == "updated"){
				realTimeUpdate(formData.entityProperty, data.value);
				if('stepCompleted' in data){
					if(data.action == 'upgrade'){
						if('salesProcessDate' in data)
							var completedOn = data.salesProcessDate;
						else
							var completedOn = '';
						fetchStepAndMarkComplete(data.stepCompleted, completedOn);
					}
					else if(data.action == 'downgrade')
						downgradeSteps(data.oldSaleProcessStep, data.stepCompleted);
				}
			}
		});
	});

	/*$('input[name="dateFrom"]').datepicker({
	    autoclose:true,
	    dateFormat:"d M yy",
	    startDate: new Date(),
	    onSelect: function( newText ){
	        nextdate = "'"+newText+"'";
	        console.log(nextdate);
        }
	});

	$( 'input[name="dateTo"]' ).datepicker( "refresh" );

	$('input[name="dateTo"]').datepicker({
	    autoclose:true,
	    dateFormat:"d M yy",
	    minDate: "'"+nextdate+"'"
	});
	*/

	// new datepicker code
	$('input[name="dateFrom"]').datepicker();
	$('input[name="dateFrom"]').change(function(){
		$('input[name="dateTo"]').datepicker('destroy');
		$('input[name="dateTo"]').datepicker({ 
			minDate:$(this).val()
		});
	});

	$('.notes-create').click(function(e){
		e.preventDefault();

		var form = $('#notes-form'),
			isFormValid = form.valid();
		var notestype=$('select#notesType').val();	

		if(isFormValid){
			var formData = {};
			if(notestype!=null)
				formData['notestype']=notestype;
			formData['note'] = $('textarea[name=note]').val();
			formData['clientId'] = currentClientId;
			$.ajax({
				url: public_url+'sales/create-client-note/save',
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "added"){
						//closeContactNoteSubview();
                        $('#notesModal').modal('hide');
						window.location.reload();
					}
				}
			});
		}
	});
	$('.check-notes-btn').click(function(e){
		$('.check-notes-type').removeClass('hidden'); 	
	});

	$('#notesModal').on("hide.bs.modal", function (e) {
		var notes=$('textarea[name=note]');
		var notestype=$('select#notesType');
		setFieldNeutral(notes);
	    setFieldNeutral(notestype);
		notes.val('');
	    notestype.val('').selectpicker('refresh');
	    $('.check-notes-type').addClass('hidden');	
	});

	/* Start: Client-Membership Modal */
		$('#membStartDate').datepicker({dateFormat:"D, d M yy"/*, minDate:0*/});

		/* start: Update Client-Membership modal reset */
		/*$('#updateClientMemb').on('show.bs.modal', function(){
			$(this).find(':input').prop('checked', false);
		});*/
		/* end: Update Client-Membership modal reset */

		/* start: Client-Membership modal reset */
		$('#editMembSub').on('show.bs.modal', function(){
			var modal = $(this),
				membershipDd = modal.find('#membership'),
				payPlanDd = modal.find('#payPlan'),
				applyDiscCb = modal.find('#applyDisc'),
				discAmtField = modal.find('#discAmt'),
				discDurDd = modal.find('#discDur'),
				membStartDateField = modal.find('#membStartDate'),
				membStatusRow = modal.find('#membStatus')/*,
				renwDateDd = modal.find('select[name="renwDate"]'),
				renwDayDd = modal.find('select[name="renwDay"]')*/;

			if(hasData(modal, 'opened')){
				//Modal is being opened for second time or greater
				membershipDd.val(membershipDd.data('val'));
				setFieldNeutral(membershipDd);

				payPlanDd.val(payPlanDd.data('val'))
				setFieldNeutral(payPlanDd);

				applyDiscCb.prop('checked', applyDiscCb.data('checked'))

				discAmtField.val(discAmtField.data('val'))
				setFieldNeutral(discAmtField);

				discDurDd.val(discDurDd.data('val'))
				setFieldNeutral(discDurDd);

				setFieldNeutral(membStartDateField);

				if(membStatusRow.length){
					var status = membStatusRow.data('val');
					membStatusRow.find('input[value="'+status+'"]').prop('checked', true);
				}

				/*renwDateDd.val(renwDateDd.data('val'))		
				setFieldNeutral(renwDateDd);

				renwDayDd.val(renwDayDd.data('val'))		
				setFieldNeutral(renwDayDd);*/

				modal.find('select').selectpicker('refresh')
			}
			else{
				//Modal is being opened for first time
				modal.data('opened', true);
				membershipDd.data('val', membershipDd.val())
				payPlanDd.data('val', payPlanDd.val())
				discAmtField.data('val', discAmtField.val())
				discDurDd.data('val', discDurDd.val())

				var val = membStartDateField.val();
				if(val)
					val = dbDateToDateString(membStartDateField.val());
				membStartDateField.data('val', val)

				if(applyDiscCb.is(':checked'))
					var checked = 'checked';
				else
					var checked = false;
				applyDiscCb.data('checked', checked)

				if(membStatusRow.length){
					var status = membStatusRow.find('input:checked').val();
					membStatusRow.data('val', status);
				}

				/*var val = renwDateDd.val();
				renwDateDd.data('val', val?val:'')

				var val = renwDayDd.val();
				renwDayDd.data('val', val?val:'')*/
			}

			membStartDateField.val(membStartDateField.data('val'))
			toggleMembdiscFields(applyDiscCb);
			setMembEndDate(/*function(){setMembPayPlanOpts(/*setMembdiscdurOpts*)}*/);
			//toggleRenwPeriod(instllPlanDd.val());
		});
		/* end: Client-Membership modal reset */

		/* start: Toggle membership status fields if membership fields updated */
		/*$('.onchange-block-membstatus').change(function(){
			if(!$(this).hasClass('bootstrap-select')){
				var membStatusRow = $('#membStatus');
				if(membStatusRow.length){
					var membStatusGroup = membStatusRow.closest('.form-group');	
					membStatusGroup.hide();	
					if(!isFieldUpdated($(this)) && !isMembChanged())
						membStatusGroup.show();
				}
			}
		})*/
		/* end: Toggle membership status fields if membership fields updated */

		/* start: Toggle discount fields based on input */
		$('#applyDisc').change(function(){
			toggleMembdiscFields($(this));
		})
		/* end: Toggle discount fields based on input */

		/* start: Toggle discount duration unit based on renewal plan choosed */
		/*$('#payPlan').change(function(){
			setMembdiscdurOpts();
		})*/
		/* end: Toggle discount duration unit based on renewal plan choosed */

		/* start: Calculate and set membership end date */
		$('#membership, #membStartDate').change(function(){
			setMembEndDate(/*function(){setMembPayPlanOpts(/*setMembdiscdurOpts*)}*/);
		})
		/* end: Calculate and set membership end date */
		
		/* start: Client-Membership modal submit */
		$('#editMembSubModelSubmit').click(function(){
			var form = $('#editMembSub form'),
				membStartDateField = form.find('#membStartDate'),
				isFormValid = form.valid();

			if(!membStartDateField.val()){
				isFormValid = false;
				setFieldInvalid(membStartDateField.closest('.form-group'), 'This field is required.');
			}

			if(isFormValid){
				$('#updateClientMemb :input').prop('checked', false);
				//if(form.find('input[name="clientMembId"]').val())
				/*if(form.data('mode') == 'edit')
					$('#updateClientMemb').modal('show'); 
				else
					submitClientMemb();*/
				var updateCase = false/*,
					modeField = form.find('input[name="mode"]')*/;
				//if(form.data('mode') == 'edit'){
				if(form.find('input[name="clientMembId"]').val()){
				//if(modeField.val() == 'edit'){
					//updateCase = isMembChanged();
					updateCase = true
					var membershipDd = form.find('#membership');
					if(membershipDd.val() == membershipDd.data('val')){
						var payPlanDd = form.find('#payPlan');
						if(payPlanDd.val() == payPlanDd.data('val')){
							var applyDiscCb = form.find('#applyDisc');
							if(applyDiscCb.prop('checked') == applyDiscCb.data('checked')){
								if(applyDiscCb.is(':checked')){
									var discAmtField = form.find('#discAmt');
									if(discAmtField.val() == discAmtField.data('val')){
										var discDurDd = form.find('#discDur');
										if(discDurDd.val() == discDurDd.data('val')){	
											updateCase = false;
										}
									}
								}
								else
									updateCase = false;
							}
						}
					}
					/*if(!updateCase)
						modeField.val();*/
				}					

				if(updateCase)
					$('#updateClientMemb').modal('show'); 
				else
					submitClientMemb();
			}
		})
		/* end: Client-Membership modal submit */

		/* start: Client-Membership update modal submit */
		$('#updateClientMembSubmit').click(function(){
			var form = $('#updateClientMemb form')/*,
				isFormValid = form.valid()*/;

			if(/*isFormValid*/validateRadioButton(form, 'updateOpt'))
				submitClientMemb();
			else{
				formGroup = form.find("input[name='updateOpt']").closest('.form-group');
				setFieldInvalid(formGroup,'Please select an option.');
			}
		})
		/* end: Client-Membership update modal submit */

		/* start: Prompt to cancel selected membership */
		$('#cancelMembOpt').click(function(e){
			e.preventDefault();
			var delPopover = cancelMembPopoverOpt;
			showPopoverOverModal($(this), delPopover);
		});
		/* end: Prompt to cancel selected membership */

        /* Start: Cancel selected membership */
        $('body').on("click", '#cancelMemb', function(e){
        	e.preventDefault();
        	var clientid = $('#editMembSub input[name="clientId"]').val();
        	$.post(public_url+'/clients/'+clientid+'/membership/delete', function(data){
				var data = JSON.parse(data);
				if(data.status == "deleted"){
					location.reload(true);
				}  
			});
		})
        /* Start: Cancel selected membership */

		/* start: Toggle renewal period fields based on installment plan */
		/*$('#editMembSub select[name="instllPlan"]').change(function(){
			toggleRenwPeriod($(this).val());
		});*/
		/* end: Toggle renewal period fields based on installment plan */
		//toggleRenwPeriod();
	/* End: Client-Membership Modal */
})

$('#printmodal').on('hide.bs.modal', function () {
	//alert(nextdate);
	$('select#appointmentstatusid').val('').selectpicker('refresh');
	$('select#printformatid').val('').selectpicker('refresh');
	var dateFrom = $('input[name="dateFrom"]');
	var dateTo = $('input[name="dateTo"]');
	setFieldNeutral($('select#appointmentstatusid'));
	setFieldNeutral($('select#printformatid'));
	setFieldNeutral(dateFrom);
	setFieldNeutral(dateTo);

});

$('#printmodal').on('show.bs.modal', function () {
    clearForm($(this).find('form'));
});

/*$('#printbtn').on('click', function () {
	var form = $('#printForm');
	var isValid = form.valid();
	if(isValid){
		var status = $('select#appointmentstatusid').val();
		//var shownotes = $('#checkbox1').val();
		var datefrom = $('input[name="dateFrom"]').val();
		var dateto = $('input[name="dateTo"]').val();
		//console.log($('#checkbox1').val());
		//console.log($('#checkbox1').prop());
		//console.log(datefrom);
		//console.log(dateto);
		window.location.href = public_url+'clients/print-appointments';
	}
});*/

$('.togglebtn').click(function(e){
	//alert("togglebtn");
	e.preventDefault();
	if($('.step-create').hasClass("hidden")){
		//alert("create has hidden");
		$('.step-create').removeClass('hidden');
		$('#form').attr('data-form-mode','create');
	    $('.step-show').addClass('hidden');
    }
    else if($('.step-show').hasClass("hidden")){
    	//alert("show has hidden");
    	$('.step-show').removeClass('hidden');
    	$('#form').attr('data-form-mode','view');
    	$('.step-create').addClass('hidden');
    }
});

/*** Change Height and Weight metric system ****/
$('body').on('click','#heightUnit',function(){
	var currUnit = $('input[name="heightUnit"]').val();
	if(currUnit == 'Metric'){
		$(this).text('Show Metric');
		$($('input[name="heightUnit"]')).val('Imperial');
		var heightMetric=$('input[name="height_metric"]').val(); 
        var inches = (heightMetric*0.393700787).toFixed(0);
        var feet = Math.floor(inches / 12);
        inches %= 12;
        if(feet == 0 || feet == 0.0)
        {
       	
        $('input[name="height_imperial_ft"]').val('');
        $('input[name="height_imperial_inch"]').val('');
         }
        else
         {
        
        $('input[name="height_imperial_ft"]').val(feet);
        $('input[name="height_imperial_inch"]').val(inches);
         }

	}else{
		$(this).text('Show Imperial');
		$($('input[name="heightUnit"]')).val('Metric');
		var height_imperial_ft=$('input[name="height_imperial_ft"]').val(); 
		var height_imperial_inch=$('input[name="height_imperial_inch"]').val(); 
        var ft = parseFloat(height_imperial_ft * 30.48);
        var inches= parseFloat(height_imperial_inch * 2.54);
        var result = parseFloat(ft + inches).toFixed(1);

         if(result == 0 || result == 0.0)
        {
       	$('input[name="height_metric"]').val('');
         }
        else
         {
        $('input[name="height_metric"]').val(result);
         }


	}
	$('div.heightMetric').toggleClass('hidden');
	$('div.heightImperial').toggleClass('hidden');
});

$('body').on('click','#weightUnit',function(){
	var currUnit = $('input[name="weightUnit"]').val();
	if(currUnit == 'Metric'){
		$(this).text('Show Metric');
		$($('input[name="weightUnit"]')).val('Imperial');
		var weight = $('input[name="weight_metric"]').val();

       weightInPounds = (weight*2.2046226218); 
       result = weightInPounds.toFixed(1);
       if(result == 0 || result == 0.0)
       {
       	$('input[name="weight_imperial"]').val('');
       }
       else
       {
      $('input[name="weight_imperial"]').val(result);
       }
	}else{
		$(this).text('Show Imperial');
		$($('input[name="weightUnit"]')).val('Metric');
	   var weight = $('input[name="weight_imperial"]').val();
       weightInPounds = (weight/2.2046226218); 
       result = weightInPounds.toFixed(0);
       if(result == 0 || result == 0.0)
       {
       	$('input[name="weight_metric"]').val('');
       }
       else
       {
      $('input[name="weight_metric"]').val(result);
       }
	}
	$('div.weightMetric').toggleClass('hidden');
	$('div.weightImperial').toggleClass('hidden');
});
   
	// $('select.smartG').on('changed.bs.select', function(e, clickedIndex, newValue){
	// 		var $this = $(this);

	// 		if(clickedIndex == 0){
	// 			$this.find('option:not([value="None"])').prop('selected', false);
	// 			$this.closest('.form-group').find('input[type="hidden"]').val('')
	// 			$this.parent().removeClass('open');
	// 		}
	// 		else{
	// 			if(newValue)
	// 				smartGoalNotes($this.attr('name'), clickedIndex)
	// 			else{
	// 				var allNotesField = $this.closest('.form-group').find('input[type="hidden"]'),
	// 				allNotes = allNotesField.val(),
	// 				opt = $this.find('option').eq(clickedIndex).attr('value');

					
					
	// 			}

	// 			$this.find('option[value="None"]').prop('selected', false)
	// 		}

	// 		$this.selectpicker('refresh')
	// 	});


// $("#smartGoal .submit").click(function(){
// 		var modal = $(this).closest('div.modal'),
// 			notes = modal.find('textarea').val();

// 		if(notes){
// 			var entity = modal.find('input[name="entity"]').val(),
// 				dd = $('select[name="'+entity+'"]'),
// 				allNotesField = dd.closest('.vp-item').find('input[type="hidden"]'),
// 				allNotes = allNotesField.val(),
// 				entityOptIdx = modal.find('input[name="entityOptIdx"]').val(),
// 				opt = dd.find('option').eq(entityOptIdx).attr('value');
			
			     
//                  if(opt == "Specific"){
// 					var html = '<div class="form-group" data-option-val="'+opt+'">\
// 					<label class="strong medinotes">Specific </label>\
// 					<input class="form-control" value="'+notes+'" name="smartGoalSpecific">\
// 					</div>';
					
// 				}
// 		          if(opt == "Measurable"){
// 					var html = '<div class="form-group" data-option-val="'+opt+'">\
// 					<label class="strong medinotes">Measurable </label>\
// 					<input class="form-control" value="'+notes+'" name="smartGoalMeasurable">\
// 					</div>';
					
// 				}
// 			      if(opt == "Achievable"){
// 					var html = '<div class="form-group" data-option-val="'+opt+'">\
// 					<label class="strong medinotes">Achievable </label>\
// 					<input class="form-control" value="'+notes+'" name="smartGoalAchievable">\
// 					</div>';
					
// 				}
// 				if(opt == "Relevant"){
// 					var html = '<div class="form-group" data-option-val="'+opt+'">\
// 					<label class="strong medinotes">Relevant </label>\
// 					<input class="form-control" value="'+notes+'" name="smartGoalRelevent">\
// 					</div>';
					
// 				}
// 				if(opt == "Time"){
// 					var html = '<div class="form-group" data-option-val="'+opt+'">\
// 					<label class="strong medinotes">Time </label>\
// 					<input class="form-control" value="'+notes+'" name="smartGoalTime">\
// 					</div>';
					
// 				}
					
// 			$('.med_notes1').append(html);
			

// 		}
// 	});


// 	function smartGoalNotes(dd, clickedIndex){
// 		var modal = $('#smartGoal');

// 		modal.find('input[name="entity"]').val(dd)
// 		modal.find('input[name="entityOptIdx"]').val(clickedIndex)
// 		modal.find('textarea').val('')
// 		modal.modal('show');
// 	}

	// $('select[name="smart_goal_option"]').change(function(){
	// 	var allVal = $(this).val();
	// 	$('.med_notes1 .form-group').each(function(){
	// 	if($.inArray($(this).data('option-val'),allVal) < 0){
	// 				$(this).remove();
	// 	}
	//     });
	// });

	$('.openSmartGoal').on('click',function(){

			$('.smartgoalNote').each(function(){
			var $this = $(this),
		     data = $this.closest('div').data('is-filled');
             if(data == 0){
             	console.log('hi');
             	$(this).modal('show');

             	return false;
             }
		    
			});
		 //    var modal= $('#specificGoal');
			// modal.modal('show');
		
		
	});

	$('#specific').on('click',function()
	{
		var modal= $('#specificGoal');
			notes = modal.find('textarea').val();
        var html = '<div class="form-group" data-option-val="Specific">\
					<label class="strong medinotes">Specific </label>\
					<input class="form-control" value="'+notes+'" name="smartGoalSpecific">\
					</div>';
		$('.goal_notes').append(html);
		$('#specificGoal').hide();
		modal.data('is-filled','1');
		var measurable= $('#MeasurableGoal');
		measurable.modal('show');
	});
   
    $('#measurable').on('click',function()
	{
		var modal= $('#MeasurableGoal');
			notes = modal.find('textarea').val();
        var html = '<div class="form-group" data-option-val="Measurable">\
					<label class="strong medinotes">Measurable </label>\
					<input class="form-control" value="'+notes+'" name="smartGoalMeasurable">\
					</div>';
		$('.goal_notes').append(html);
		$('#MeasurableGoal').hide();
		modal.data('is-filled','1');
		var achievable= $('#AchievableGoal');
		achievable.modal('show');
	});

	$('#achievable').on('click',function()
	{
		var modal= $('#AchievableGoal');
			notes = modal.find('textarea').val();
        var html = '<div class="form-group" data-option-val="Achievable">\
					<label class="strong medinotes">Achievable </label>\
					<input class="form-control" value="'+notes+'" name="smartGoalAchievable">\
					</div>';
		$('.goal_notes').append(html);
		$('#AchievableGoal').hide();
		modal.data('is-filled','1');
		var relevant= $('#RelevantGoal');
		relevant.modal('show');
	});

	$('#relevent').on('click',function()
	{
		var modal= $('#RelevantGoal');
			notes = modal.find('textarea').val();
        var html = '<div class="form-group" data-option-val="Relevant">\
					<label class="strong medinotes">Relevant </label>\
					<input class="form-control" value="'+notes+'" name="smartGoalRelevent">\
					</div>';
		$('.goal_notes').append(html);
		$('#RelevantGoal').hide();
		modal.data('is-filled','1');
		var relevant= $('#TimeGoal');
		relevant.modal('show');
	});
	$('#time').on('click',function()
	{
		var modal= $('#TimeGoal');
			notes = modal.find('textarea').val();
        var html = '<div class="form-group" data-option-val="Time">\
					<label class="strong medinotes">Time </label>\
					<input class="form-control" value="'+notes+'" name="smartGoalTime">\
					</div>';
		$('.goal_notes').append(html);
		$('#TimeGoal').hide();
		modal.data('is-filled','1');
		$('.openSmartGoal').hide();
	});




