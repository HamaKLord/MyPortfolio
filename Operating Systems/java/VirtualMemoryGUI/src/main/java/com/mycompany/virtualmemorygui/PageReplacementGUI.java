package com.mycompany.virtualmemorygui;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.HashMap;
import java.util.Map;

public class PageReplacementGUI extends Frame implements ActionListener, FocusListener {
    private static final long serialVersionUID = 1L;

    private static JMenuBar nav_bar;
    private static Map<String, JButton> gui_buttons = new HashMap<>();
    private static JComboBox<String> algoDropdown;
    private static JTextField pagesField;
    private static JTextArea outputArea;

    // Define buttons names
    private static String[] buttons_names = {"Initialize", "Run", "Stop"};
    
    // Create the navigation bar
    private static JMenuBar createNavBar() {
        JMenuBar top_bar = new JMenuBar();
        top_bar.setOpaque(true);
        top_bar.setBackground(new Color(255, 0, 0));
        top_bar.setPreferredSize(new Dimension(200, 40));
        
//        JMenu fileMenu = new JMenu("File");
//        JMenuItem fileItem1 = new JMenuItem("Item menu 1");
//        JMenuItem fileItem2 = new JMenuItem("Item menu 2");
//        fileItem1.addActionListener(new ActionListener() {
//            @Override
//            public void actionPerformed(ActionEvent e) {
//                showFileItem1();
//            }
//        });
//        fileItem2.addActionListener(new ActionListener() {
//            @Override
//            public void actionPerformed(ActionEvent e) {
//                showFileItem2();
//            }
//        });
//        fileMenu.add(fileItem1);
//        fileMenu.add(fileItem2);
//        
//        JMenu plotMenu = new JMenu("Help");
//        JMenuItem chartItem = new JMenuItem("Chart 1");
//        chartItem.addActionListener(new ActionListener() {
//            @Override
//            public void actionPerformed(ActionEvent e) {
//                showPlotItem();
//            }
//        });
//        plotMenu.add(chartItem);
        
        JMenu helpMenu = new JMenu("Help");
        JMenuItem helpItem = new JMenuItem("Help message");
        helpItem.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                showHelpDialog();
            }
        });
        helpMenu.add(helpItem);

        JMenu aboutMenu = new JMenu("About");
        JMenuItem aboutItem = new JMenuItem("About message");
        aboutItem.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                showAboutDialog();
            }
        });
        aboutMenu.add(aboutItem);

        // Add menus to the top bar
//        top_bar.add(fileMenu);
//        top_bar.add(plotMenu);
        top_bar.add(Box.createHorizontalGlue());
        top_bar.add(helpMenu);
        top_bar.add(aboutMenu);
        
        return top_bar;
    }

    // Show File Item 1 message in a dialog box
//    private static void showFileItem1() {
//        JOptionPane.showMessageDialog(null, "File Item 1 Selected", "File Menu", JOptionPane.INFORMATION_MESSAGE);
//    }
//
//    // Show File Item 2 message in a dialog box
//    private static void showFileItem2() {
//        JOptionPane.showMessageDialog(null, "File Item 2 Selected", "File Menu", JOptionPane.INFORMATION_MESSAGE);
//    }
//
//    // Show Plot item message in a dialog box
//    private static void showPlotItem() {
//        JOptionPane.showMessageDialog(null, "Chart 1 Plot Selected", "Plot Menu", JOptionPane.INFORMATION_MESSAGE);
//    }

    // Show Help message in a dialog box
    private static void showHelpDialog() {
        String helpMessage = "How To Use:\n\n"
                + "1. Enter Page References: Type a sequence of page numbers (e.g., 1, 2, 3, 4) in the 'Page References' field.\n"
                + "2. Select Algorithm: Choose either 'FIFO' or 'LRU' from the 'Algorithm' dropdown menu.\n"
                + "3. Run the Simulation: Click the 'Run' button to execute the selected page replacement algorithm and view the results.\n"
                + "4. Stop the Simulation: Click the 'Stop' button to halt the current simulation.\n";
        
        JOptionPane.showMessageDialog(null, helpMessage, "Help", JOptionPane.INFORMATION_MESSAGE);
    }

    // Show About message in a dialog box
    private static void showAboutDialog() {
        String aboutMessage = "This program has been developed by Mohammed Kamal Ali.";
        JOptionPane.showMessageDialog(null, aboutMessage, "About", JOptionPane.INFORMATION_MESSAGE);
    }

    // Create buttons based on button names
    private static Map<String, JButton> createButtons(String[] button_names) {
        Map<String, JButton> buttons = new HashMap<>();
        for (String name : button_names) {
            JButton button = new JButton(name);
            button.addActionListener(new ActionListener() {
                @Override
                public void actionPerformed(ActionEvent e) {
                    if (e.getSource() == button) {
                        if (name.equals("Run")) {
                            runPageReplacement();
                        } else if (name.equals("Stop")) {
                            stopPageReplacement();
                        }
                    }
                }
            });
            buttons.put(name, button);
        }
        return buttons;
    }

    // Create control panel with buttons
    private static JPanel createButtonsPane() {
        Map<String, JButton> buttonsDict = createButtons(buttons_names);
        JPanel buttonsPane = new JPanel();
        for (String buttonName : buttons_names) {
            buttonsPane.add(buttonsDict.get(buttonName), BorderLayout.CENTER);
        }
        return buttonsPane;
    }

    // Initialize the components (Text fields, combo boxes)
    private static void initializeComponents() {
        pagesField = new JTextField();
        algoDropdown = new JComboBox<>(new String[] { "FIFO", "LRU" });
        outputArea = new JTextArea(10, 30);
    }

    // Setup main GUI panels
    private static JSplitPane createGuiPanels() {
        JPanel inputPanel = new JPanel();
        inputPanel.setLayout(new GridLayout(2, 2));
        
        inputPanel.add(new JLabel("Page References:"));
        inputPanel.add(pagesField);
        inputPanel.add(new JLabel("Algorithm:"));
        inputPanel.add(algoDropdown);
        
        JPanel buttonsPanel = createButtonsPane();

        JSplitPane controlPanel = new JSplitPane(JSplitPane.VERTICAL_SPLIT, inputPanel, buttonsPanel);
        controlPanel.setOneTouchExpandable(true);

        return controlPanel;
    }

    // Function to run page replacement algorithm
private static void runPageReplacement() {
    try {
        String pages = pagesField.getText();
        String algo = (String) algoDropdown.getSelectedItem();

        // Use relative path (points to python/use_cpp.py inside project folder)
        String scriptPath = "python/use_cpp.py";

        // Call Python script for page replacement
        ProcessBuilder builder = new ProcessBuilder(
            "python",
            scriptPath,
            pages,
            algo
        );
            builder.redirectErrorStream(true);
            Process process = builder.start();

            // Read Python script output
            BufferedReader reader = new BufferedReader(new InputStreamReader(process.getInputStream()));
            String line;
            StringBuilder output = new StringBuilder();
            while ((line = reader.readLine()) != null) {
                output.append(line).append("\n");
            }
            outputArea.setText(output.toString());
        } catch (Exception ex) {
            outputArea.setText("Error: " + ex.getMessage());
        }
    }

    // Function to stop page replacement (optional)
    private static void stopPageReplacement() {
        outputArea.setText("Page replacement stopped.");
    }

    // Create and display the GUI
    public static void createAndShowGUI() {
        JFrame frame = new JFrame("Virtual Memory Management");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(600, 400);

        initializeComponents();
        
        // Set menu bar and content pane
        frame.setJMenuBar(createNavBar());
        JSplitPane guiPanels = createGuiPanels();
        
        // Add output area
        frame.add(new JScrollPane(outputArea), BorderLayout.CENTER);
        frame.add(guiPanels, BorderLayout.NORTH);

        // Display the frame
        frame.setVisible(true);
    }

    // Main function to invoke the GUI
    public static void main(String[] args) {
        SwingUtilities.invokeLater(PageReplacementGUI::createAndShowGUI);
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        // Handle button click events if needed
    }

    @Override
    public void focusGained(FocusEvent e) {
        // Handle focus gained
    }

    @Override
    public void focusLost(FocusEvent e) {
        // Handle focus lost
    }
}
