#include <iostream>
#include <vector>
#include <algorithm>

extern "C" {
    void fifo(int pages[], int n, int capacity) {
        std::vector<int> frame;
        int pageFaults = 0;

        for (int i = 0; i < n; ++i) {
            auto it = std::find(frame.begin(), frame.end(), pages[i]);
            if (it == frame.end()) {
                if (frame.size() == capacity) frame.erase(frame.begin());
                frame.push_back(pages[i]);
                pageFaults++;
            }
            std::cout << "Frame: ";
            for (int x : frame) std::cout << x << " ";
            std::cout << std::endl;
        }
        std::cout << "Total Page Faults: " << pageFaults << std::endl;
    }

    void lru(int pages[], int n, int capacity) {
        std::vector<int> frame;
        int pageFaults = 0;

        for (int i = 0; i < n; ++i) {
            auto it = std::find(frame.begin(), frame.end(), pages[i]);
            if (it == frame.end()) {
                if (frame.size() == capacity) frame.erase(frame.begin());
                frame.push_back(pages[i]);
                pageFaults++;
            } else {
                frame.erase(it);
                frame.push_back(pages[i]);
            }
            std::cout << "Frame: ";
            for (int x : frame) std::cout << x << " ";
            std::cout << std::endl;
        }
        std::cout << "Total Page Faults: " << pageFaults << std::endl;
    }
}

int main() {
    // For testing, manually call FIFO or LRU here if needed.
    return 0;
}
