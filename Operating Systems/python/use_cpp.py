import ctypes
import os

# Load the shared library
lib_path = r"cpp\PageReplacement\bin\Debug\PageReplacement.dll"


page_lib = ctypes.CDLL(lib_path)

# Define the C++ function signatures
page_lib.fifo.argtypes = [ctypes.POINTER(ctypes.c_int), ctypes.c_int, ctypes.c_int]
page_lib.lru.argtypes = [ctypes.POINTER(ctypes.c_int), ctypes.c_int, ctypes.c_int]

def call_fifo():
    pages = [7, 0, 1, 2, 0, 3, 0, 4, 2, 3, 0, 3, 2]
    n = len(pages)
    capacity = 3
    array_type = ctypes.c_int * n
    page_lib.fifo(array_type(*pages), n, capacity)

def call_lru():
    pages = [7, 0, 1, 2, 0, 3, 0, 4, 2, 3, 0, 3, 2]
    n = len(pages)
    capacity = 3
    array_type = ctypes.c_int * n
    page_lib.lru(array_type(*pages), n, capacity)

if __name__ == "__main__":
    print("FIFO Algorithm")
    call_fifo()
    print("\nLRU Algorithm")
    call_lru()
