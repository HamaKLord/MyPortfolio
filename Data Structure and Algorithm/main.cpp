#include <iostream>
#include <vector>
#include <string>
#include <iomanip>

using namespace std;

// Structure to hold the student's marks
struct StudentMarks {
    string studentName;
    string subject;
    int marks;

    StudentMarks() : studentName(""), subject(""), marks(0) {}

    StudentMarks(const string& name, const string& subj, int m) :
         studentName(name), subject(subj), marks(m) {}
};

// Custom Hash Table class
class HashTable {
private:
    vector<StudentMarks> table;
    vector<bool> occupied;
    int size;

    // Hash function to calculate hash value of a key
    int hashFunction(const string& key) {
        int hash = 0;
        for (char c : key) {
            hash = (hash * 31 + c) % size;
        }
        return hash;
    }

    // Function to handle collisions using linear probing
    int findSlot(const string& key) {
        int index = hashFunction(key);
        while (occupied[index] && table[index].studentName != key) {
            index = (index + 1) % size;
        }
        return index;
    }

public:
    HashTable(int s) : size(s), table(s), occupied(s, false) {}

    // Function to insert or update marks for a student
    // Insertion:
    void insertMarks(const string& studentName, const string& subject, int marks) {
        int index = findSlot(studentName);
        table[index] = StudentMarks(studentName, subject, marks);
        occupied[index] = true;
        cout << "Inserted/Updated marks for " << studentName << " in " << subject << endl;
    }

    // Function to search and display marks for a student
    // Searching:
    void searchMarks(const string& studentName, const string& subject) {
        int index = findSlot(studentName);
        if (occupied[index] && table[index].subject == subject) {
            cout << studentName << " has " << table[index].marks << " marks in " << subject << endl;
        } else {
            cout << "No marks found for " << studentName << " in " << subject << endl;
        }
    }

    // Function to delete marks for a student
    // Deletion:
    void deleteMarks(const string& studentName, const string& subject) {
        int index = findSlot(studentName);
        if (occupied[index] && table[index].subject == subject) {
            occupied[index] = false;
            cout << "Deleted marks for " << studentName << " in " << subject << endl;
        } else {
            cout << "No marks found for " << studentName << " in " << subject << endl;
        }
    }

    // Function to display all student details in a table
    // Display:
    void displayAllStudents() {
        cout << left << setw(20) << "Student Name" << setw(20)
        << "Subject" << setw(10) << "Marks" << endl;

        cout << "------------------------------------------------------------" << endl;
        for (int i = 0; i < size; ++i) {
            if (occupied[i]) {
            cout << left << setw(20) << table[i].studentName
            << setw(20) << table[i].subject
            << setw(10) << table[i].marks << endl;
            }
        } }
       };
  // Function to display the menu
        void displayMenu() {
    cout << "\nStudent Marks Management System\n";
    cout << "1. Insert/Update Marks\n";
    cout << "2. Search Marks\n";
    cout << "3. Delete Marks\n";
    cout << "4. Display All Student Details\n";
    cout << "5. Exit\n";
    cout << "Enter your choice: ";
           }

// Main Function:
// The main function provides a menu-driven interface for the user to interact with the system.
// It allows the user to insert, search, delete, and display student marks:
int main() {
    HashTable hashTable(100);  // Initialize hash table with size 100
    int choice;
    string studentName, subject;
    int marks;

    while (true) {
        displayMenu();
        cin >> choice;

        switch (choice) {
            case 1:
                cout << "Enter student name: ";
                cin >> studentName;
                cout << "Enter subject: \n";
                cin >> subject;
                cout << "Enter marks: ";
                cin >> marks;
                hashTable.insertMarks(studentName, subject, marks);
                break;
            case 2:
                cout << "Enter student name: ";
                cin >> studentName;
                cout << "Enter subject: ";
                cin >> subject;
                hashTable.searchMarks(studentName, subject);
                break;
            case 3:
                cout << "Enter student name: ";
                cin >> studentName;
                cout << "Enter subject: ";
                cin >> subject;
                hashTable.deleteMarks(studentName, subject);
                break;
            case 4:
                hashTable.displayAllStudents();
                break;
            case 5:
                cout << "Exiting..." << endl;
                return 0;
            default:
                cout << "Invalid choice. Please try again." << endl;
        }
    }
    return 0;
}
