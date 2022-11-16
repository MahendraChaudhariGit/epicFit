var femaleAreasForEx = {
	'abdominals': ["742,1514,716,1501,702,1481,687,1454,666,1418,647,1381,627,1345,603,1306,585,1289,569,1264,561,1235,559,1213,567,1195,581,1162,584,1141,574,1071,564,1017,561,996,555,974,563,964,575,953,597,952,613,945,630,932,654,916,676,893,708,857,738,859,753,860,778,884,792,902,829,922,869,940,911,949,925,962,927,980,922,1020,920,1067,918,1138,940,1195,939,1217,923,1264,889,1300,847,1374,811,1445,787,1498,768,1508","1636,1467,1629,1441,1625,1409,1619,1370,1613,1319,1611,1299,1611,1288,1592,1265,1551,1220,1549,1194,1558,1149,1566,1090,1558,1050,1572,1049,1589,1038,1571,1018,1590,1011,1618,990,1617,972,1636,953,1664,943,1673,945,1691,921,1700,909,1697,939,1691,1000,1683,1030,1674,1077,1665,1171,1667,1219,1642,1400","2101,1234,2070,1230,2062,1224,2054,1195,2063,1174,2073,1140,2074,1082,2072,1070,2071,1021,2082,1024,2090,1079,2096,1136,2099,1169,2104,1220","2377,1245,2367,1233,2366,1207,2376,1170,2395,1109,2413,1046,2426,1008,2427,1038,2418,1090,2412,1145,2423,1182,2436,1215,2430,1224"],
	'adductors': ["676,2021,671,1988,667,1946,665,1916,666,1867,669,1805,673,1739,675,1687,668,1660,642,1600,614,1536,584,1466,565,1407,546,1358,555,1340,558,1296,573,1301,589,1318,646,1381,671,1413,702,1456,733,1483,743,1494,765,1502,778,1511,781,1498,787,1478,808,1443,830,1394,860,1340,888,1306,908,1295,944,1275,944,1292,943,1344,941,1371,917,1438,895,1511,868,1571,845,1622,831,1656,827,1704,829,1773,837,1827,834,1883,836,1944,827,1996,812,2027,796,2032,780,2022,772,2008,770,1961,774,1849,780,1733,774,1627,772,1550,776,1515,767,1508,754,1509,739,1526,745,1554,744,1608,736,1670,731,1715,733,1772,738,1870,741,1935,738,2022,727,2027,698,2034","2213,2090,2207,2055,2200,1973,2195,1908,2184,1824,2184,1728,2170,1653,2141,1598,2131,1580,2146,1576,2164,1573,2191,1557,2214,1547,2221,1543,2217,1669,2215,1718,2215,1765,2219,1844,2223,1929,2223,2033","2258,2044,2256,2016,2254,1988,2254,1940,2256,1903,2258,1852,2260,1801,2264,1736,2258,1667,2251,1621,2249,1581,2252,1554,2280,1568,2340,1583,2334,1602,2320,1633,2304,1686,2290,1705,2296,1716,2285,1849,2279,1940,2276,1995"],
    /*'all': ["738,2764,702,2770,687,2766,663,2761,649,2764,630,2762,625,2750,631,2734,635,2719,644,2696,659,2661,652,2635,651,2622,651,2596,647,2550,632,2472,604,2346,595,2263,589,2175,590,2131,595,2096,596,2064,591,2024,584,1986,568,1905,546,1775,522,1645,506,1559,505,1485,511,1411,522,1354,534,1291,544,1256,558,1213,580,1162,584,1140,571,1055,557,980,545,923,537,969,524,1016,518,1096,514,1192,502,1251,485,1324,471,1375,468,1426,474,1513,477,1609,473,1635,466,1636,457,1623,455,1591,451,1555,450,1588,450,1628,443,1661,435,1660,429,1647,425,1629,422,1604,421,1623,422,1656,416,1671,407,1673,399,1660,398,1639,396,1617,395,1585,391,1562,384,1588,378,1635,373,1652,364,1654,355,1641,355,1617,359,1588,356,1580,343,1585,340,1576,339,1557,336,1538,343,1508,354,1478,382,1436,396,1417,399,1388,403,1326,402,1238,401,1178,406,1128,414,1081,426,1029,435,962,444,903,452,848,452,779,453,724,456,690,457,670,468,634,495,609,526,597,572,596,627,583,656,557,662,532,657,504,648,465,641,436,633,436,624,426,611,395,609,369,610,356,621,354,629,360,629,335,630,296,643,258,666,226,700,214,741,209,787,220,820,243,836,277,846,321,843,366,853,360,861,361,856,410,846,427,838,440,830,445,824,476,808,499,809,534,815,555,849,579,911,595,946,596,976,604,993,622,1012,653,1019,689,1020,783,1018,884,1029,989,1034,1047,1051,1110,1062,1177,1074,1281,1075,1396,1078,1427,1100,1487,1114,1530,1104,1589,1085,1633,1069,1643,1060,1649,1052,1657,1038,1661,1027,1662,1025,1650,1036,1635,1048,1624,1051,1599,1043,1592,1030,1581,1030,1568,1033,1537,1031,1514,1021,1487,1021,1450,1016,1396,1001,1338,981,1279,959,1211,946,1161,936,1096,935,1041,930,983,928,965,923,1014,918,1069,917,1126,927,1165,960,1242,979,1322,993,1402,1004,1478,997,1586,966,1743,946,1857,934,1948,919,2060,920,2111,925,2148,921,2229,911,2319,892,2407,865,2520,855,2581,853,2639,846,2664,848,2674,859,2712,868,2740,875,2756,865,2764,805,2768,767,2765,754,2751,749,2737,754,2718,757,2696,756,2670,762,2639,762,2611,769,2586,774,2548,776,2490,772,2414,768,2333,763,2264,762,2231,765,2195,772,2162,780,2107,776,2056,771,2006,770,1938,771,1882,776,1809,778,1735,777,1652,773,1621,771,1560,773,1528,776,1516,766,1510,752,1514,741,1528,744,1565,746,1623,741,1645,734,1691,732,1739,737,1825,742,1946,735,2058,732,2081,728,2101,735,2150,743,2194,747,2225,742,2341,734,2416,729,2518,732,2562,744,2613,742,2633,748,2671,743,2706,747,2739,746,2752","1684,2768,1657,2769,1620,2766,1582,2767,1532,2762,1435,2762,1414,2755,1394,2739,1386,2705,1392,2667,1400,2627,1405,2586,1406,2524,1398,2449,1388,2382,1382,2341,1370,2288,1372,2221,1383,2165,1391,2137,1407,2100,1422,2067,1432,2028,1435,2003,1436,1981,1420,1911,1406,1839,1401,1780,1398,1747,1394,1684,1391,1616,1382,1572,1359,1534,1334,1491,1323,1458,1318,1423,1320,1394,1335,1329,1361,1280,1380,1240,1401,1181,1408,1145,1418,1059,1413,992,1406,942,1387,899,1358,835,1341,769,1331,712,1327,656,1335,613,1355,563,1385,504,1395,468,1389,427,1360,378,1354,339,1353,307,1361,275,1394,227,1444,205,1511,206,1552,214,1613,241,1627,271,1640,309,1643,344,1640,366,1655,389,1668,411,1653,436,1650,447,1647,462,1646,476,1637,489,1632,517,1621,523,1607,526,1581,525,1555,526,1533,551,1517,584,1550,621,1571,652,1600,692,1641,727,1672,758,1708,794,1722,830,1720,880,1700,913,1698,930,1692,984,1684,1029,1673,1085,1671,1150,1665,1240,1660,1303,1640,1397,1648,1472,1651,1609,1638,1728,1620,1856,1594,2053,1571,2109,1551,2172,1536,2317,1526,2432,1523,2508,1527,2573,1540,2614,1594,2663,1658,2704,1713,2720,1753,2732,1766,2738,1767,2754,1739,2764","2180,2766,2155,2759,2131,2761,2111,2754,2117,2745,2120,2738,2130,2733,2141,2705,2147,2680,2140,2654,2142,2621,2137,2574,2124,2517,2107,2444,2087,2350,2079,2297,2074,2240,2070,2173,2071,2135,2076,2100,2074,2055,2065,1991,2056,1928,2044,1855,2031,1781,2012,1672,2001,1621,1992,1570,1988,1519,1989,1483,1993,1435,2000,1392,2015,1317,2025,1271,2042,1220,2065,1170,2073,1137,2072,1058,2068,996,2065,972,2059,1006,2057,1038,2058,1072,2053,1118,2046,1170,2035,1212,2022,1249,2007,1300,1984,1365,1971,1422,1970,1459,1969,1491,1962,1518,1957,1529,1959,1553,1961,1583,1948,1592,1944,1597,1937,1598,1945,1621,1940,1638,1951,1640,1967,1654,1963,1667,1948,1663,1941,1667,1929,1652,1912,1640,1899,1629,1886,1590,1876,1548,1883,1522,1894,1483,1907,1450,1916,1424,1916,1392,1918,1344,1919,1279,1926,1205,1933,1155,1949,1089,1960,1041,1965,967,1974,894,1976,828,1972,768,1973,715,1976,667,1989,635,2011,609,2043,599,2060,598,2089,598,2154,579,2184,552,2186,519,2183,493,2166,465,2158,437,2150,432,2140,408,2134,378,2135,359,2146,359,2148,365,2150,349,2149,321,2152,284,2169,245,2209,217,2250,207,2299,215,2325,226,2339,241,2361,285,2364,354,2368,360,2381,353,2388,365,2381,409,2369,430,2357,436,2353,433,2347,466,2339,486,2335,527,2340,562,2358,583,2393,598,2437,602,2485,605,2507,616,2525,639,2542,681,2546,779,2546,850,2555,922,2568,1009,2583,1084,2597,1186,2597,1291,2597,1402,2606,1433,2624,1459,2648,1493,2660,1547,2659,1564,2660,1586,2647,1587,2643,1595,2642,1646,2626,1661,2620,1653,2618,1634,2611,1576,2606,1576,2603,1610,2598,1649,2597,1670,2589,1678,2579,1675,2572,1662,2574,1631,2574,1617,2568,1646,2564,1666,2554,1668,2545,1649,2548,1620,2548,1564,2544,1587,2541,1620,2533,1641,2527,1643,2521,1621,2521,1570,2519,1541,2524,1480,2528,1432,2527,1405,2517,1347,2496,1261,2481,1173,2476,1099,2474,1030,2455,949,2450,918,2441,968,2427,1038,2414,1131,2410,1155,2421,1178,2442,1234,2464,1311,2483,1406,2493,1495,2492,1558,2473,1653,2457,1742,2431,1885,2412,2004,2401,2078,2411,2168,2405,2277,2394,2374,2382,2433,2364,2497,2350,2568,2345,2604,2346,2648,2344,2676,2348,2700,2350,2710,2356,2730,2365,2740,2368,2750,2365,2761,2343,2760,2312,2765,2267,2759,2246,2745,2242,2734,2246,2728,2248,2717,2248,2701,2251,2677,2254,2657,2251,2636,2260,2605,2266,2570,2267,2503,2262,2443,2255,2359,2247,2285,2249,2242,2257,2182,2266,2126,2262,2077,2256,2036,2254,1983,2253,1929,2256,1869,2260,1803,2261,1719,2256,1665,2250,1612,2249,1572,2253,1540,2234,1514,2225,1525,2220,1534,2223,1573,2220,1640,2217,1716,2218,1821,2224,1925,2224,2040,2217,2083,2216,2139,2227,2199,2233,2263,2226,2367,2223,2476,2221,2571,2230,2615,2236,2639,2233,2666,2237,2678,2241,2698,2240,2724,2237,2743,2231,2761"],*/
    'back-low': ["1475,1401,1320,1402,1323,1384,1326,1361,1337,1329,1359,1279,1383,1233,1400,1183,1406,1155,1415,1118,1419,1037,1442,1040,1453,1073,1458,1082,1454,1111,1450,1133,1451,1180","2482,1402,2342,1402,2230,1401,2061,1401,2000,1402,2004,1371,2013,1330,2020,1290,2034,1239,2050,1202,2062,1175,2074,1137,2074,1090,2071,1046,2092,1049,2406,1049,2424,1052,2417,1096,2412,1152,2422,1182,2443,1236,2463,1302"],
    'back-mid': ["1445,1047,1421,1050,1419,1040,1416,1008,1414,974,1408,946,1395,909,1377,876,1360,831,1378,841,1401,838,1414,913,1421,952","2421,1084,2075,1075,2070,1037,2065,970,2070,932,2074,902,2074,836,2144,844,2154,843,2161,841,2338,844,2370,851,2408,848,2442,839,2449,906,2438,967,2423,1059"],
    'back-upper': ["1416,981,1411,958,1404,935,1395,911,1372,866,1353,815,1340,761,1329,700,1329,655,1340,608,1346,590,1399,594,1455,596,1473,596,1463,605,1443,612,1408,621,1387,632,1361,651,1350,671,1365,691,1372,704,1393,768,1388,789,1397,790,1404,846,1421,963","2229,1035,2228,1002,2225,968,2193,903,2165,859,2158,842,2121,846,2074,838,2065,808,2053,781,2038,741,2030,696,2053,690,2084,680,2099,676,2102,667,2070,651,2019,622,2011,611,2028,602,2047,596,2075,597,2113,599,2184,599,2348,602,2415,602,2438,599,2476,601,2503,611,2491,632,2457,641,2423,657,2398,671,2411,677,2431,684,2471,695,2487,699,2470,751,2458,793,2442,840,2405,848,2342,850,2294,929,2265,996,2251,1038,2243,1049"],
    'biceps': ["455,1079,449,1051,453,1026,445,978,440,935,443,916,449,878,453,847,460,818,478,781,505,748,536,706,548,732,551,758,544,789,527,836,529,868,544,907,517,966,507,1001,522,1048,519,1098,499,1084,487,1052","994,1079,1002,1036,1014,994,1019,950,1022,919,1017,882,1010,837,994,801,968,762,931,717,920,738,924,775,940,823,943,850,944,879,937,895,930,936,950,1005,952,1021,936,1046,936,1061,940,1091,952,1078,965,1058","1565,1064,1554,1046,1536,996,1504,941,1499,927,1498,888,1497,848,1513,814,1532,765,1540,847,1553,938,1555,1003"],
    'calves': ["621,2423,613,2394,605,2352,597,2289,597,2256,611,2342,618,2385","727,2496,717,2451,708,2387,698,2328,691,2276,698,2229,718,2192,735,2158,741,2181,748,2232,747,2277,740,2373","779,2490,775,2453,772,2403,771,2355,764,2273,764,2226,767,2191,775,2157,787,2188,810,2245,815,2278,796,2399","881,2445,881,2423,892,2362,902,2316,910,2259,916,2203,923,2165,920,2219,911,2304","1391,2697,1392,2676,1397,2649,1403,2612,1407,2556,1407,2503,1399,2459,1392,2407,1386,2363,1373,2301,1371,2246,1374,2207,1387,2154,1400,2120,1420,2085,1437,2064,1450,2052,1454,2076,1468,2105,1469,2150,1456,2207,1446,2244,1427,2371,1419,2400,1414,2468,1418,2532,1415,2593,1408,2647,1400,2695","2178,2706,2175,2667,2170,2611,2159,2572,2136,2519,2124,2499,2113,2467,2101,2419,2086,2347,2078,2285,2072,2212,2071,2152,2074,2105,2091,2076,2103,2055,2120,2030,2138,2048,2159,2075,2168,2078,2172,2054,2183,2052,2195,2068,2206,2089,2213,2113,2220,2153,2231,2224,2234,2271,2227,2371,2224,2441,2205,2525,2201,2577,2206,2637,2215,2695,2193,2712,2183,2711","2276,2693,2280,2651,2283,2607,2285,2549,2273,2483,2261,2435,2258,2385,2250,2297,2248,2256,2253,2217,2263,2158,2268,2119,2265,2091,2274,2074,2300,2041,2307,2055,2318,2070,2343,2039,2353,2033,2371,2054,2388,2074,2402,2097,2410,2146,2410,2200,2404,2305,2396,2366,2372,2473,2359,2518,2323,2591,2320,2622,2316,2708,2300,2714"],
    'chest': ["707,866,703,857,705,830,703,813,698,803,690,790,682,777,664,764,652,756,635,754,606,750,582,751,568,752,550,756,550,743,547,731,539,714,537,697,534,681,531,665,540,656,569,665,583,658,604,658,631,646,646,640,676,644,709,650,727,648,735,645,762,650,778,648,788,641,807,635,821,634,828,636,846,641,864,651,892,657,913,654,930,654,937,656,935,673,940,688,931,720,924,745,904,753,876,750,844,746,821,750,788,762,766,784,755,793,754,806,751,829,752,861,737,868","1535,755,1536,724,1543,683,1546,666,1537,640,1529,635,1547,636,1558,635,1565,645,1573,655,1593,683,1612,697,1631,716,1650,734,1666,751,1642,748,1603,751,1579,750"],
    'forearms': ["402,1393,403,1381,402,1359,403,1322,401,1249,402,1189,404,1147,407,1116,416,1072,425,1033,430,1003,435,957,440,931,445,941,446,983,454,1020,453,1057,455,1072,471,1058,486,1042,497,1068,513,1085,519,1083,519,1057,515,1043,503,1025,508,1006,519,986,521,977,525,998,526,1015,521,1036,522,1064,520,1080,519,1095,518,1133,515,1172,505,1232,487,1303,469,1396","1017,1397,1008,1360,994,1316,972,1252,956,1197,940,1112,938,1094,936,1052,936,1023,930,982,930,965,938,985,953,1025,944,1044,937,1044,937,1066,938,1084,940,1094,951,1079,956,1065,960,1052,968,1044,977,1056,984,1067,990,1071,993,1050,999,1023,1005,995,1014,966,1019,927,1018,913,1026,953,1030,992,1030,1030,1037,1061,1042,1089,1056,1148,1066,1205,1071,1272,1073,1359,1076,1399,1040,1394","1478,1419,1468,1374,1457,1314,1452,1280,1447,1237,1443,1196,1445,1160,1449,1135,1462,1120,1483,1098,1496,1078,1502,1076,1498,1054,1492,1012,1485,993,1478,981,1479,967,1490,971,1514,982,1526,996,1537,1011,1562,1050,1565,1079,1562,1132,1552,1200,1545,1273,1537,1361,1537,1402","1916,1406,1917,1380,1918,1343,1919,1297,1921,1252,1927,1199,1934,1154,1944,1113,1952,1104,1963,1135,1972,1155,1970,1201,1972,1211,1981,1180,1990,1147,2001,1127,2006,1102,2024,1109,2035,1099,2045,1085,2057,1071,2057,1090,2053,1121,2046,1170,2034,1212,2013,1272,1989,1348,1975,1406","2528,1407,2520,1360,2506,1302,2492,1242,2479,1164,2477,1124,2477,1078,2497,1102,2514,1100,2523,1130,2519,1191,2523,1219,2528,1183,2528,1158,2537,1146,2553,1128,2567,1114,2575,1108,2578,1085,2578,1064,2585,1084,2589,1115,2596,1167,2596,1236,2595,1305,2595,1362,2598,1404,2558,1415"],
    'gluteus': ["1420,1667,1413,1643,1404,1611,1390,1580,1384,1575,1371,1549,1345,1515,1325,1468,1320,1415,1322,1377,1330,1345,1348,1304,1366,1272,1391,1256,1411,1247,1437,1241,1447,1237,1452,1258,1458,1294,1450,1356,1443,1383,1445,1399,1437,1477,1427,1520,1421,1541,1416,1572","1989,1505,1990,1481,1993,1437,2002,1391,2011,1342,2019,1294,2032,1251,2039,1233,2056,1233,2081,1247,2109,1268,2146,1285,2185,1321,2201,1335,2209,1358,2224,1362,2239,1370,2248,1355,2256,1340,2266,1328,2286,1312,2298,1313,2317,1294,2333,1285,2372,1264,2393,1248,2414,1236,2427,1231,2437,1233,2445,1244,2449,1256,2460,1283,2469,1332,2481,1405,2491,1466,2493,1503,2495,1524,2485,1525,2464,1532,2457,1540,2451,1552,2445,1572,2437,1597,2434,1609,2427,1655,2418,1675,2415,1693,2411,1678,2411,1651,2407,1624,2395,1599,2381,1585,2363,1587,2328,1584,2281,1568,2256,1553,2252,1539,2236,1516,2228,1520,2214,1537,2198,1557,2176,1568,2147,1572,2109,1576,2077,1581,2072,1604,2065,1640,2057,1664,2047,1620,2036,1589,2020,1547,2011,1515"],
    'hamstrings': ["1464,2116,1454,2097,1449,2076,1447,2052,1447,2029,1442,2010,1437,2024,1432,2010,1436,1995,1432,1971,1426,1934,1413,1877,1404,1806,1396,1724,1394,1664,1388,1588,1383,1574,1394,1580,1406,1608,1416,1634,1416,1618,1412,1566,1411,1536,1419,1519,1426,1511,1437,1499,1446,1520,1456,1550,1458,1579,1464,1617,1478,1665,1489,1729,1495,1798,1493,1869,1497,1972,1504,2026,1505,2070,1491,2093","2077,2110,2076,2066,2069,2009,2062,1972,2052,1895,2038,1815,2023,1732,2012,1671,1997,1607,1998,1590,2004,1568,2002,1538,2006,1520,2012,1508,2026,1535,2032,1564,2048,1613,2055,1641,2056,1620,2059,1598,2066,1582,2083,1572,2115,1574,2138,1572,2165,1566,2185,1561,2198,1547,2218,1531,2220,1549,2224,1581,2222,1629,2216,1714,2216,1781,2221,1872,2224,1914,2225,1982,2223,2040,2217,2096,2218,2143,2210,2130,2200,2093,2182,2059,2161,2017,2142,1972,2140,1992,2117,2039","2268,2139,2267,2102,2263,2077,2260,2052,2257,2033,2255,2006,2254,1978,2255,1938,2256,1891,2260,1831,2262,1773,2263,1712,2254,1649,2250,1593,2251,1554,2256,1538,2269,1556,2292,1572,2339,1579,2364,1581,2387,1579,2405,1594,2414,1636,2419,1644,2421,1622,2425,1609,2429,1584,2435,1563,2444,1547,2451,1529,2461,1516,2470,1538,2471,1583,2476,1625,2464,1706,2449,1786,2433,1877,2414,1980,2406,2030,2400,2096,2396,2098,2380,2074,2357,2040,2337,2002,2329,1976,2286,2082,2277,2097"],
    'latissimus-dorsi': ["1376,1252,1385,1227,1399,1197,1402,1176,1408,1146,1417,1101,1419,1036,1414,977,1402,929,1375,872,1360,830,1375,842,1388,838,1403,838,1404,862,1411,902,1415,935,1420,962,1428,989,1433,1012,1444,1042,1456,1069,1453,1099,1452,1123,1430,1170,1408,1210,1394,1239,1386,1247","2208,1343,2191,1334,2186,1317,2176,1305,2160,1294,2132,1271,2096,1244,2096,1190,2091,1103,2075,991,2067,966,2074,911,2072,835,2093,839,2117,842,2160,841,2171,859,2187,881,2215,936,2230,968,2239,1009,2245,1029,2256,1008,2270,957,2287,925,2308,890,2316,884,2329,869,2333,851,2343,842,2367,847,2405,844,2445,834,2444,873,2446,892,2452,914,2441,950,2420,1022,2395,1087,2378,1147,2370,1206,2369,1237,2382,1242,2365,1258,2321,1283,2288,1311,2257,1343"],
    'neck': ["719,664,705,653,701,641,694,633,668,633,623,630,582,618,550,606,527,599,538,597,565,597,604,590,632,579,655,563,661,537,658,507,653,476,667,495,682,512,695,520,715,530,736,534,767,528,788,515,801,505,809,503,808,527,807,535,814,551,827,571,852,580,893,592,916,598,932,598,916,605,890,618,855,625,814,630,784,633,759,646,748,663","1329,683,1329,663,1331,641,1339,611,1350,576,1365,544,1377,522,1391,492,1397,464,1391,430,1404,431,1426,433,1460,435,1490,437,1516,437,1523,440,1524,461,1534,483,1546,499,1587,519,1599,524,1584,527,1559,524,1546,532,1533,549,1520,569,1514,585,1520,606,1543,624,1522,624,1506,610,1485,601,1464,606,1424,616,1387,636","2125,678,2090,659,2057,650,2034,638,2014,627,2008,614,2016,605,2039,601,2062,598,2087,598,2139,586,2172,572,2186,548,2185,498,2162,453,2179,445,2202,437,2246,428,2299,430,2327,435,2343,440,2354,446,2341,486,2336,524,2336,548,2345,570,2369,591,2408,600,2467,603,2496,608,2504,617,2484,632,2460,640,2441,657,2415,670,2390,673,2372,667,2340,663,2309,658,2263,646,2216,652"],
    'quadriceps': ["597,2053,593,2029,591,2007,581,1972,574,1933,565,1880,551,1798,536,1719,524,1654,512,1594,506,1568,511,1552,513,1531,514,1504,517,1488,524,1469,535,1453,547,1421,557,1386,557,1365,555,1339,554,1305,557,1287,572,1294,579,1316,589,1352,603,1396,618,1434,635,1477,648,1504,655,1510,659,1488,665,1456,671,1432,677,1434,687,1457,701,1489,719,1525,722,1551,733,1586,732,1614,732,1665,736,1670,735,1708,731,1750,736,1817,739,1895,740,1939,740,2014,720,2025,698,2033,681,2026,656,2023,632,2027,614,2031","773,2020,771,1986,770,1940,772,1893,774,1844,778,1787,779,1724,777,1655,771,1601,771,1577,778,1562,790,1525,797,1500,807,1477,815,1444,823,1429,832,1437,835,1472,838,1504,842,1511,854,1485,866,1452,881,1418,892,1381,899,1349,904,1309,921,1286,939,1278,939,1308,940,1348,943,1375,954,1423,973,1467,988,1530,997,1597,981,1665,965,1759,948,1857,935,1947,921,2037,916,2050,901,2046,888,2026,858,2022,830,2016,815,2019,807,2029","1527,2054,1527,2020,1524,1973,1524,1924,1523,1880,1526,1843,1531,1811,1538,1760,1543,1706,1548,1660,1558,1619,1566,1596,1575,1598,1578,1584,1579,1567,1576,1550,1581,1532,1589,1511,1600,1490,1601,1472,1608,1459,1608,1434,1611,1395,1608,1367,1616,1371,1622,1384,1627,1408,1633,1428,1635,1439,1638,1411,1642,1418,1644,1449,1649,1479,1651,1508,1652,1557,1647,1642,1635,1753,1624,1834,1616,1894,1608,1964,1595,1973,1597,1990,1585,2011,1566,2043,1541,2054"],
    'shoulders': ["453,857,454,816,453,786,453,759,454,733,456,703,458,671,466,647,474,630,492,612,512,600,533,596,553,610,600,630,638,644,625,649,601,656,576,660,544,661,535,666,534,679,537,685,539,711,532,725,513,745,497,768,476,798","834,639,854,636,876,630,896,619,922,601,948,598,975,607,1003,633,1016,665,1019,681,1019,735,1019,796,1017,865,1018,903,1012,889,1008,861,999,827,987,799,961,763,935,732,924,719,930,694,929,658,903,659,854,653","1499,892,1472,863,1445,845,1423,823,1406,791,1389,756,1376,719,1366,697,1345,679,1342,664,1352,656,1374,642,1392,638,1415,640,1446,627,1465,618,1496,605,1506,605,1528,618,1536,626,1542,644,1543,667,1545,715,1532,772,1522,799,1509,836","1978,845,1976,816,1973,790,1972,745,1973,707,1973,679,1987,638,1993,632,2007,638,2035,646,2056,651,2074,662,2105,672,2107,683,2091,684,2070,687,2048,698,2032,703,2034,715,2019,747,2009,784","2543,844,2527,829,2505,803,2500,782,2498,762,2474,742,2478,716,2462,698,2406,688,2403,676,2420,673,2444,658,2466,650,2496,641,2510,640,2520,623,2528,638,2543,678,2544,749,2545,806"],
    'trapezius': ["600,631,583,625,572,616,551,605,542,596,556,598,585,593,612,587,637,580,651,567,658,566,654,578,646,592,624,610,629,630","842,628,843,618,844,607,831,589,824,578,815,563,808,542,806,535,816,554,832,571,861,584,900,592,941,597,948,601,892,614","1341,770,1334,735,1330,700,1328,662,1332,635,1339,605,1354,567,1369,536,1379,518,1394,481,1397,455,1395,438,1417,434,1416,461,1415,489,1420,516,1427,548,1444,579,1471,595,1463,605,1433,612,1398,625,1375,637,1356,660,1345,687","2229,1043,2226,1013,2220,980,2216,941,2186,900,2159,839,2137,787,2111,713,2099,680,2095,666,2070,652,2037,636,2014,623,2008,616,2017,605,2041,596,2075,598,2117,593,2146,583,2174,568,2190,541,2207,523,2223,487,2226,461,2225,445,2250,441,2269,441,2319,441,2320,460,2324,501,2329,522,2338,546,2344,569,2373,589,2419,601,2463,600,2489,604,2498,613,2487,629,2455,643,2419,659,2406,692,2381,746,2366,799,2336,864,2300,920,2283,949,2278,972,2263,1006,2249,1046"],
    'triceps': ["525,1013,521,992,523,966,527,948,534,922,540,906,546,916,535,965","936,1004,930,981,926,963,932,941,938,965","1452,1096,1448,1082,1448,1063,1437,1035,1425,994,1414,956,1406,900,1399,862,1394,804,1387,768,1397,771,1413,808,1449,843,1458,881,1468,919,1483,976,1491,1003,1492,1059,1478,1082,1463,1094","1959,1076,1956,1065,1959,1044,1962,1009,1965,968,1969,927,1975,878,1977,832,1992,806,2011,780,2018,747,2039,740,2046,764,2058,787,2077,829,2075,895,2067,959,2060,1003,2057,1066,2045,1087,1979,1082","2476,1078,2474,1033,2459,968,2450,916,2444,901,2439,856,2438,830,2450,796,2462,773,2468,759,2477,741,2492,747,2500,758,2507,786,2528,813,2543,839,2547,865,2552,900,2560,941,2567,994,2571,1020,2576,1057,2569,1077,2549,1084,2498,1086,2486,1084"]
},
	maleAreasForEx = {
	'abdominals': ["239,516,231,505,211,469,194,439,179,421,182,397,185,364,178,333,176,295,184,298,200,301,216,304,228,299,239,294,247,297,258,296,280,301,297,303,318,297,327,294,327,314,325,337,317,365,317,386,320,417,314,437,265,502,247,518","559,454,550,432,529,425,514,423,511,413,510,383,511,352,521,351,529,345,515,336,523,335,533,330,515,315,529,316,543,308,553,303,557,321,561,386","706,432,694,402,686,377,683,387,680,401,680,417,682,427","799,428,798,412,806,391,813,369,815,360,815,382,818,400,820,418,808,428"],
    'adductors': ["229,668,222,654,222,635,223,587,220,559,203,512,189,462,184,429,196,442,210,460,221,485,238,510,243,521,246,545,245,582,243,627,242,663","306,444,306,456,303,474,297,499,291,526,275,554,271,594,274,643,276,662,270,675,258,670,252,657,251,606,246,562,246,539,250,513,289,457,295,448","740,707","739,709,736,692,735,655,731,621,723,564,717,534,741,529,752,521,752,565,747,665","766,707,759,682,759,652,756,628,757,586,753,551,757,522,776,525,794,527,779,601"],
    /*'all': ["246,925,225,924,207,921,200,912,203,905,212,892,214,878,211,856,200,808,188,765,185,743,184,721,188,696,187,671,185,650,178,620,167,576,162,525,165,489,172,446,179,420,181,396,187,377,183,346,179,340,177,351,173,375,170,403,164,422,153,456,149,478,148,488,148,513,147,536,145,558,139,562,136,546,137,532,134,548,135,559,131,574,125,573,122,558,125,543,125,534,122,552,119,569,114,578,110,572,110,546,114,533,109,559,102,569,96,564,96,548,100,529,102,523,94,528,83,528,83,520,91,514,99,503,105,487,115,484,118,472,119,452,122,415,123,379,126,357,134,326,140,287,138,253,147,226,165,214,191,202,217,187,225,174,218,153,211,141,208,125,211,119,217,121,213,112,213,96,222,81,244,71,261,68,276,76,289,87,292,112,292,120,297,120,298,133,293,146,286,152,283,186,293,193,320,209,345,221,358,235,366,256,368,276,363,298,367,327,372,358,376,393,373,438,375,478,380,489,392,505,403,524,408,531,401,536,389,529,390,543,393,570,387,578,381,565,377,540,377,555,377,582,370,585,367,562,364,541,366,567,359,580,354,574,353,554,352,539,352,565,346,568,342,535,342,502,340,468,327,411,324,387,324,361,322,347,318,369,315,395,318,407,320,445,325,485,329,515,329,568,311,650,310,685,310,709,315,752,299,818,293,862,294,878,294,894,306,913,296,921,268,925,257,916,256,894,257,868,261,837,258,795,255,748,258,721,261,705,254,676,252,649,251,593,246,567,246,543,247,524,246,518,241,516,244,527,245,573,243,611,242,658,239,691,238,707,244,744,242,785,241,826,248,862,253,911","545,924,529,923,507,924,480,920,450,921,431,920,426,907,428,894,432,878,436,855,434,809,431,780,426,749,430,732,447,683,453,666,450,598,451,568,452,545,451,534,438,495,440,468,447,443,438,401,431,352,422,301,422,283,417,254,419,238,425,218,438,200,455,166,458,145,451,114,457,90,486,70,517,69,544,81,555,100,557,129,553,135,557,146,557,157,549,159,547,170,542,176,538,187,526,188,512,188,510,203,517,217,536,235,553,268,557,293,561,347,564,433,560,458,562,532,559,579,542,641,533,653,525,689,513,708,500,771,488,862,496,877,525,893,546,899,556,902,561,912,557,922","740,929,725,927,711,922,698,914,696,907,702,904,709,897,709,872,698,816,688,763,687,733,691,699,687,648,673,584,669,536,671,514,675,476,682,423,681,402,683,382,678,349,675,374,675,408,661,465,657,489,659,531,656,565,651,568,647,563,647,538,646,556,644,571,641,580,634,575,635,551,635,541,633,558,631,578,627,586,621,579,620,562,624,539,619,555,617,574,609,575,607,564,609,542,610,525,603,533,591,530,591,524,604,516,612,497,624,489,624,422,624,382,628,360,634,325,636,304,631,271,633,255,642,235,669,216,695,203,716,185,711,148,705,144,699,121,705,117,708,122,706,106,714,84,730,71,753,68,774,78,787,99,788,121,791,128,788,149,781,149,778,166,775,175,777,184,803,202,837,217,854,229,865,260,863,298,872,348,879,383,881,422,884,478,894,488,904,505,916,522,909,526,898,522,903,542,904,567,897,570,889,542,887,533,890,559,889,577,881,578,878,555,876,534,877,566,873,574,865,563,863,533,863,562,858,563,854,548,850,516,853,487,846,451,831,408,827,378,821,342,815,362,814,386,820,419,826,445,834,485,838,537,821,629,815,675,816,716,818,757,806,810,792,860,792,886,788,896,797,904,800,909,802,919,770,920,756,921,750,906,752,895,754,873,759,857,760,805,757,759,761,728,765,711,758,669,756,626,756,599,753,579,754,544,757,519,751,524,753,538,753,585,749,611,748,638,750,670,745,696,741,713,746,763,742,812,740,840,746,876,746,907,745,921"],*/
    'back-low': ["487,469,468,469,450,466,439,465,446,454,449,441,456,424,463,399,459,377,470,400","677,476,699,479,732,479,756,482,783,478,822,480,830,473,827,441,819,418,817,394,815,363,750,364,683,366,685,384"],
    'back-mid': ["461,355,455,318,453,299,422,293,437,329,449,354","684,364,679,347,681,323,675,302,686,303,716,302,773,298,802,299,820,297,819,324,823,340,815,363"],
    'back-upper': ["455,354,450,358,440,335,426,302,426,314,422,306,423,289,415,254,421,231,430,210,438,204,448,203,466,209,478,214,459,226,447,228,441,234,446,244,447,286,443,300","708,304,694,306,675,304,671,282,672,270,677,258,673,241,695,239,679,229,660,219,679,214,702,216,742,212,784,215,818,208,828,211,822,225,796,231,799,234,830,249,827,267,827,280,821,290,817,303,794,304,785,312,767,337,757,366,743,365"],
    'biceps': ["168,383,156,374,147,366,138,376,130,361,130,348,134,328,139,297,145,279,160,262,170,253,173,281,169,325,163,337,157,349,170,361","325,390,324,364,322,346,329,317,326,293,328,276,338,263,355,278,364,306,368,341,367,367,358,381,351,376,336,383","498,379,492,359,488,333,488,308,496,288,506,276,513,295,516,321,515,347,508,376"],
    'calves': ["214,848,205,832,202,810,189,769,184,737,185,720,188,697,194,699,192,732,197,756,201,791","237,817,231,787,226,744,226,729,232,717,237,706,240,724,243,751","263,825,259,791,256,770,256,742,260,715,269,730,271,756,269,795","289,854,291,839,298,813,303,788,307,761,306,707,308,704,313,737,310,769,296,831","446,889,436,891,429,896,432,875,435,841,433,798,429,771,426,745,436,712,446,685,454,664,459,681,467,673,479,666,489,686,494,697,491,724,479,767,468,781,457,799,454,814","731,907,720,894,719,877,717,851,711,824,699,813,693,799,689,766,688,739,691,709,693,690,699,671,711,684,719,674,732,692,742,709,746,748,741,799,737,866,741,891,739,903","767,898,763,887,767,863,766,833,762,816,759,788,759,754,760,731,765,712,765,697,777,685,790,671,795,680,805,661,814,677,813,703,817,729,816,770,806,793,792,816,787,847,778,883,775,895"],
    'chest': ["246,300,236,297,227,304,203,305,184,298,181,288,173,265,170,257,160,265,160,257,169,241,186,228,208,217,225,213,245,218,252,224,259,221,264,216,285,215,312,226,337,246,344,268,335,261,332,273,327,297,312,303,287,308","549,305,534,301,514,295,505,276,511,249,512,230,514,213,521,222,539,240,555,276,555,293"],
    'forearms': ["148,472,131,472,118,470,122,440,122,413,123,380,129,353,133,336,134,363,137,371,144,365,152,366,162,374,168,377,168,364,154,355,157,347,164,340,170,335,170,357,173,370,169,405","330,339,342,359,331,367,330,380,343,373,351,368,361,376,367,357,367,346,371,361,374,370,376,389,374,424,373,453,374,479,355,479,344,478,337,447,328,418,324,388,325,369,324,348","516,479,508,483,493,487,488,479,483,458,472,425,469,396,468,364,472,334,482,347,494,364,502,370,511,407","655,487,634,487,626,484,626,459,625,425,625,387,625,370,632,354,634,333,638,363,639,380,651,395,662,409,667,393,673,378,674,396","855,481,848,458,835,419,829,380,837,378,845,382,843,392,851,389,859,381,863,369,862,357,864,348,873,362,879,372,878,396,880,422,881,460,883,480"],
    'gluteus': ["489,559,480,541,465,528,457,531,453,539,447,524,440,498,438,473,445,448,452,442,461,441,471,432,479,445,488,465,492,485,487,510,486,529","722,538,708,539,692,540,685,532,683,520,681,498,679,476,679,456,681,431,687,423,714,436,739,450,749,468,756,469,768,455,777,442,809,422,817,419,824,446,825,480,828,506,820,524,811,534,787,531,766,523,757,517,743,529"],
    'hamstrings': ["491,703,482,686,472,652,469,675,464,694,457,702,452,683,452,663,451,617,450,588,452,553,453,535,459,534,467,533,474,533,482,542,486,552,486,521,496,513,503,524,509,550,511,571,510,601,501,671","692,694,690,655,679,616,671,572,670,532,676,526,679,510,685,520,691,539,696,538,708,534,727,534,748,529,752,535,754,553,753,572,751,594,748,617,747,637,748,667,743,697,739,710,727,695,710,666","766,708,758,672,757,632,757,597,755,568,757,543,759,529,781,528,796,530,806,531,817,534,822,545,824,521,829,511,838,519,835,546,832,583,818,634,814,681,806,676,800,657"],
    'latissimus-dorsi': ["182,338,175,341,170,319,172,287,174,276","319,350,313,344,321,334,323,311,324,290,331,271,332,299,329,324","452,299,456,320,462,350,464,364,454,351,443,317,439,299","771,457,742,458,719,446,701,429,700,419,689,391,682,375,678,350,680,320,677,301,709,301,731,332,746,357,754,359,762,344,769,329,785,307,793,297,818,299,818,331,822,343,807,393,799,430"],
    'neck': ["243,222,227,216,200,214,175,213,185,206,211,190,224,185,224,170,234,178,244,185,258,189,267,180,279,170,285,164,285,185,302,198,331,216,306,218,256,225","474,214,461,220,446,222,436,230,429,238,420,251,417,245,423,221,436,202,452,175,459,145,465,137,476,139,487,147,503,148,503,160,506,169,520,179,533,182,538,187,520,188,512,188,509,197,508,207,517,217","725,231,699,228,681,225,671,214,683,210,702,198,716,188,717,171,713,148,722,142,742,137,760,136,772,141,780,146,780,155,776,168,776,180,788,194,806,204,826,212,823,218,804,231,774,231,762,220,745,222"],
    'quadriceps': ["188,688,185,654,183,634,171,594,163,536,166,483,173,446,181,421,199,439,207,467,219,514,233,552,244,573,243,614,244,657,239,664,226,670,222,652,214,644,205,651,198,669","255,675,253,654,253,629,251,596,253,585,260,557,272,528,282,500,295,461,297,445,317,426,320,439,321,469,328,507,329,560,320,606,313,645,309,671,300,678,291,671,289,654,277,656,271,674","531,667,521,674,508,679,505,665,511,638,518,613,526,585,532,567,529,543,525,527,523,519,543,538,550,528,532,505,517,491,520,485,523,459,525,440,527,422,554,433,560,450,561,490,560,543,558,578,536,643,531,653"],
    'shoulders': ["142,290,139,273,138,250,143,233,158,221,180,209,205,213,215,215,200,223,178,231","363,314,357,292,344,276,333,264,332,248,318,237,288,219,300,218,324,216,338,215,358,235,367,265","489,316,473,293,460,276,451,260,448,244,435,233,445,229,461,218,479,215,505,215,516,215,513,242,505,275","674,272,662,269,654,277,639,293,634,291,632,271,635,251,646,230,662,218,676,213,692,228,704,236,682,246","823,270,810,249,815,236,801,233,807,227,820,217,827,208,836,218,851,226,861,238,863,254,864,273,862,289,844,274,834,262,826,262"],
    //'trapezius': ["207,214,181,209,195,203,215,190","295,219,291,195,286,186,305,199,326,213","456,352,452,358,442,347,431,319,422,295,416,261,415,247,423,222,437,200,456,160,459,138,464,145,463,172,474,188,484,202,498,216,484,215,460,218,447,227,437,239,435,270,444,312","746,368,734,352,730,338,711,313,707,294,703,258,694,238,669,226,660,221,670,216,697,202,717,186,722,173,729,157,729,139,739,139,752,140,764,140,765,164,770,176,782,189,802,204,823,214,815,227,790,228,795,236,799,249,793,270,792,291,786,312,764,348,750,367"],
    'trapezius': ["228,222,211,220,198,216,192,217,202,212,217,205,230,197,236,195", "323,224,321,212,313,200,313,193,330,206,348,219,353,221", "490,368,481,353,469,329,459,301,452,272,451,256,457,234,466,218,484,193,495,171,497,152,498,144,503,150,503,164,503,177,516,196,524,208,538,221,529,221,517,220,498,225,480,237,471,259,483,324,493,357", "808,377,801,371,792,351,779,327,767,311,765,287,764,264,760,247,743,239,721,230,720,224,736,219,762,203,777,190,787,181,790,158,790,144,800,145,809,144,824,145,825,155,830,174,843,191,867,207,899,218,901,226,887,234,871,241,863,247,863,262,862,293,857,313,832,347,826,363,820,378"],
    'triceps': ["172,360,170,334,172,327,180,333","325,360,322,342,329,329","467,373,462,352,452,316,450,291,457,276,467,285,473,294","654,382,641,371,637,354,634,339,634,319,636,302,636,284,644,277,652,267,669,266,674,270,673,286,680,308,680,338,676,362,672,383,663,379","828,386,829,368,823,344,818,333,818,306,823,285,824,269,817,265,828,261,836,260,847,263,862,286,863,309,864,331,860,362,852,376"]
};   

function loadBodyAreasForExercise(areas, modal){
    var b = $(modal).find('.body')[0], 
        multiplier = $(b).width() / (areas==maleAreasForEx?1000:3000), 
        m = document.getElementById('Map'), 
        t = $('meta[name="public_url"]').attr('content')+'result/fitness-planner/boddymapper/'+(areas==maleAreasForEx?'MALE':'FEMALE'), 
        prePartSrc = '';

    b.src = t + '/body.gif';
   
    while(m.firstChild)
        m.removeChild(m.firstChild);
    
    for(var i in areas){
        var c=new Image();
        c.src=t+'/'+i+'.gif';
        
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
                    b.src = t + '/body.gif';
            };
            a.onclick = function(){
                var part = this.getAttribute('part'),
                    bodyPartDd = $('#muscle_group');
                   /* bodyPartDd = $('#bodyPartDd');*/

                bodyPartDd.find('option[data-part="'+part+'"]').prop('selected',true);
                bodyPartDd.selectpicker('refresh');

                b.src = t + '/' + this.getAttribute('part') + '.gif';
                prePartSrc = b.src;
                /*clearTimeout(keySearchTimeoutId);
                keySearchTimeoutId = setTimeout(getExercises, 1000);*/
                getExercises();
            };
            m.appendChild(a);
        }
    }

}