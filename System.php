<?php

namespace jackh\dashboard;

class System
{
    public function memory_usage()
    {
        switch (strtolower(php_uname("s"))) {
            case "darwin":
                return $this->mac_memory_usage();
            default:
                return $this->linux_memory_usage();
        }
    }

    public function cpu_usage_percent()
    {
        $load = sys_getloadavg();
        return $load[0];
    }

    public function disk_usage()
    {
        $disk_free_space  = disk_free_space('/');
        $disk_total_space = disk_total_space('/');
        $disk_usage       = ($disk_total_space - $disk_free_space) / $disk_total_space * 100;
        return [
            "usage_percent" => number_format($disk_usage, 2),
            "totle_size"    => $this->getSymbolByQuantity($disk_total_space),
            "free_size"     => $this->getSymbolByQuantity($disk_free_space),
        ];
    }

    public function linux_memory_usage()
    {
        $free         = shell_exec('free');
        $free         = (string) trim($free);
        $free_arr     = explode("\n", $free);
        $mem          = explode(" ", $free_arr[1]);
        $mem          = array_filter($mem);
        $mem          = array_merge($mem);
        $memory_usage = $mem[2] / $mem[1] * 100;
        return [
            "usage_percent" => number_format($memory_usage, 2),
            "totle_size"    => $this->getSymbolByQuantity($mem[1]),
            "free_size"     => $this->getSymbolByQuantity($mem[2]),
        ];
    }

    public function mac_memory_usage()
    {
        $free_size = call_user_func(function () {
            $free_pages_str = shell_exec('vm_stat | grep free');
            // Examples: "Pages free: 1267301."
            // P.S. Page size is 4096 bytes
            $free_pages = trim(preg_split("/:[\s]+/", $free_pages_str)[1], ".");
            // $free_page * 4096 bytes
            return (int) ($free_pages * 4096);
        });
        $physical_size = call_user_func(function () {
            $physical_mem_str = shell_exec("sysctl -a | grep 'hw.memsize'");
            // Example: "hw.memsize: 17179869184", unit is bytes
            $physical_byte = preg_split("/:[\s]+/", $physical_mem_str)[1];
            return (int) $physical_byte;
        });

        return [
            "usage_percent" => number_format(($physical_size - $free_size) / $physical_size * 100, 2),
            "totle_size"    => $this->getSymbolByQuantity($physical_size),
            "free_size"     => $this->getSymbolByQuantity($free_size),
        ];
    }

    protected function getSymbolByQuantity($bytes)
    {
        $symbols = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $exp     = floor(log($bytes) / log(1024));

        return sprintf('%.2f ' . $symbols[$exp], ($bytes / pow(1024, floor($exp))));
    }
}
