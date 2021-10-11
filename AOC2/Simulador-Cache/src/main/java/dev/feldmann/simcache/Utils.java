package dev.feldmann.simcache;

import java.util.List;

public class Utils {

    public static String toBinary(long number) {
        return String.format("%32s", Long.toUnsignedString(number, 2)).replace(' ', '0');
    }

    public static String toBinary(int integer) {
        return String.format("%32s", Integer.toUnsignedString(integer, 2)).replace(' ', '0');
    }

    public static String listToString(List list) {
        return listToString(list, " ");
    }

    public static String listToString(List list, String separator) {
        StringBuilder string = new StringBuilder();
        for (Object obj : list) {
            if (string.length() > 0) {
                string.append(separator);
            }
            string.append(obj.toString());
        }
        return string.toString();
    }
}
